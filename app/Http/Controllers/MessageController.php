<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Muestra la lista de conversaciones del usuario autenticado.
     * 
     * GET /messages
     */
    public function index()
    {
        $user = Auth::user();

        // Obtiene todos los usuarios con los que ha tenido conversaciones
        $conversationUsers = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($message) use ($user) {
                // Retorna el otro usuario de la conversación
                return $message->sender_id === $user->id ? $message->receiver : $message->sender;
            })
            ->unique('id');

        return view('messages.index', compact('conversationUsers'));
    }

    /**
     * Muestra la conversación con un usuario específico.
     * 
     * GET /messages/{user_id}
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        // Obtiene la conversación entre los dos usuarios
        $messages = Message::conversationBetween($currentUser->id, $user->id);

        // Marca como leídos los mensajes recibidos
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('user', 'messages'));
    }

    /**
     * Envía un nuevo mensaje.
     * 
     * POST /messages
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:1000',
        ], [
            'receiver_id.required' => 'El destinatario es requerido.',
            'receiver_id.exists' => 'El destinatario seleccionado no existe.',
            'booking_id.exists' => 'La reserva seleccionada no existe.',
            'subject.max' => 'El asunto no puede exceder 200 caracteres.',
            'message.required' => 'El mensaje es requerido.',
            'message.max' => 'El mensaje no puede exceder 1000 caracteres.',
        ]);

        // Verifica que no se envíe un mensaje a sí mismo
        if ($validated['receiver_id'] == Auth::id()) {
            return back()->withErrors(['error' => 'No puedes enviarte mensajes a ti mismo.']);
        }

        // Crea el mensaje
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'booking_id' => $validated['booking_id'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
        ]);

        // Si es una petición AJAX, retorna JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load(['sender', 'receiver', 'booking']),
            ]);
        }

        return back()->with('success', 'Mensaje enviado exitosamente.');
    }

    /**
     * Obtiene los mensajes no leídos del usuario autenticado.
     * 
     * GET /messages/unread
     */
    public function unread()
    {
        $unreadMessages = Message::unreadForUser(Auth::id());

        return response()->json([
            'count' => $unreadMessages->count(),
            'messages' => $unreadMessages,
        ]);
    }

    /**
     * Marca un mensaje como leído.
     * 
     * PATCH /messages/{id}/read
     */
    public function markAsRead(Message $message)
    {
        // Verifica que el usuario sea el receptor del mensaje
        if ($message->receiver_id !== Auth::id()) {
            abort(403, 'No tienes permiso para marcar este mensaje como leído.');
        }

        $message->markAsRead();

        return response()->json(['success' => true]);
    }
}
