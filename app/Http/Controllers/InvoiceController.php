<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Muestra todas las facturas del usuario autenticado (profesional o cliente).
     * 
     * GET /invoices
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Si es administrador, mostrar todas las facturas
        if ($user->isAdmin()) {
            $query = Invoice::with(['booking.client', 'booking.professional', 'booking.service']);
            
            // Estadísticas globales del sistema
            $stats = [
                'total_invoiced' => Invoice::sum('total'),
                'pending_amount' => Invoice::where('status', 'pending')->sum('total'),
                'paid_amount' => Invoice::where('status', 'paid')->sum('total'),
                'total_invoices' => Invoice::count(),
            ];

            // Filtro por estado
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filtro por búsqueda (número de factura, cliente o profesional)
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('booking.client', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('booking.professional', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $invoices = $query->orderBy('issued_at', 'desc')->paginate(15);

            return view('invoices.admin', compact('invoices', 'stats'));
        }

        // Obtiene las facturas según el rol
        if ($user->isPro()) {
            // Facturas del profesional (servicios prestados)
            $query = Invoice::whereHas('booking', function ($q) use ($user) {
                $q->where('pro_id', $user->id);
            })->with(['booking.client', 'booking.service']);
            
            // Estadísticas para profesional
            $stats = [
                'total_invoiced' => Invoice::whereHas('booking', function ($q) use ($user) {
                    $q->where('pro_id', $user->id);
                })->sum('total'),
                'pending_amount' => Invoice::whereHas('booking', function ($q) use ($user) {
                    $q->where('pro_id', $user->id);
                })->where('status', 'pending')->sum('total'),
                'paid_amount' => Invoice::whereHas('booking', function ($q) use ($user) {
                    $q->where('pro_id', $user->id);
                })->where('status', 'paid')->sum('total'),
            ];
        } else {
            // Facturas del cliente (servicios contratados)
            $query = Invoice::whereHas('booking', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['booking.professional', 'booking.service']);
            
            // Estadísticas para cliente
            $stats = [
                'total_invoiced' => Invoice::whereHas('booking', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->sum('total'),
                'pending_amount' => Invoice::whereHas('booking', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('status', 'pending')->sum('total'),
                'paid_amount' => Invoice::whereHas('booking', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('status', 'paid')->sum('total'),
            ];
        }

        // Filtro por estado
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('issued_at', 'desc')->paginate(15);

        return view('invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Muestra una factura específica.
     * 
     * GET /invoices/{invoice}
     */
    public function show(Invoice $invoice)
    {
        // Verifica que el usuario tenga permiso
        $user = Auth::user();
        $booking = $invoice->booking;

        // Admin puede ver todas las facturas
        if (!$user->isAdmin() && $booking->pro_id !== $user->id && $booking->user_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta factura.');
        }

        $invoice->load(['booking.client', 'booking.professional', 'booking.service']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Genera una factura para una reserva completada.
     * 
     * POST /bookings/{booking}/generate-invoice
     */
    public function generate(Booking $booking)
    {
        $user = Auth::user();

        // Verifica que el usuario sea el profesional
        if ($booking->pro_id !== $user->id) {
            abort(403, 'No tienes permiso para generar esta factura.');
        }

        // Verifica que la reserva esté completada
        if ($booking->status !== 'completed') {
            return back()->withErrors(['error' => 'Solo se pueden generar facturas para servicios completados.']);
        }

        // Verifica que no exista ya una factura
        if ($booking->invoice) {
            return back()->withErrors(['error' => 'Ya existe una factura para esta reserva.']);
        }

        // Crea la factura
        $invoice = Invoice::create([
            'booking_id' => $booking->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'issued_at' => now(),
            'total' => $booking->total_price,
            'status' => 'pending',
        ]);

        // Genera el PDF
        $this->generatePDF($invoice);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Factura generada exitosamente.');
    }

    /**
     * Descarga el PDF de una factura.
     * 
     * GET /invoices/{invoice}/download
     */
    public function download(Invoice $invoice)
    {
        $user = Auth::user();
        $booking = $invoice->booking;

        // Verifica que el usuario tenga permiso (admin puede descargar todas)
        if (!$user->isAdmin() && $booking->pro_id !== $user->id && $booking->user_id !== $user->id) {
            abort(403, 'No tienes permiso para descargar esta factura.');
        }

        // Siempre regenerar el PDF para asegurar que esté actualizado
        $this->generatePDF($invoice);

        // Descargar el PDF
        return Storage::download($invoice->pdf_path, $invoice->invoice_number . '.pdf');
    }

    /**
     * Marca una factura como pagada.
     * 
     * POST /invoices/{invoice}/mark-paid
     */
    public function markAsPaid(Invoice $invoice)
    {
        $user = Auth::user();

        // Verifica que el usuario sea el profesional
        if ($invoice->booking->pro_id !== $user->id) {
            abort(403, 'No tienes permiso para modificar esta factura.');
        }

        // Eliminar el PDF antiguo antes de actualizar
        if ($invoice->pdf_path && Storage::exists($invoice->pdf_path)) {
            Storage::delete($invoice->pdf_path);
        }

        $invoice->markAsPaid();
        
        // Regenerar el PDF con el estado actualizado
        $this->generatePDF($invoice);

        return back()->with('success', 'Factura marcada como pagada.');
    }

    /**
     * Genera el archivo PDF de la factura.
     */
    protected function generatePDF(Invoice $invoice)
    {
        // Eliminar PDF existente si hay uno
        if ($invoice->pdf_path && Storage::exists($invoice->pdf_path)) {
            Storage::delete($invoice->pdf_path);
        }

        // Refrescar el modelo para obtener el estado más reciente
        $invoice->refresh();
        
        // Carga las relaciones necesarias
        $invoice->load(['booking.client', 'booking.professional', 'booking.service']);

        // Genera el PDF
        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice,
            'booking' => $invoice->booking,
        ]);

        // Guarda el PDF
        $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::put($filename, $pdf->output());

        // Actualiza la ruta en la base de datos
        $invoice->update(['pdf_path' => $filename]);
    }
}
