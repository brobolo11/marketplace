# 💳 SISTEMA DE PAGOS SIMULADOS - SERVICIOS PRO

## 📋 Descripción General

Sistema de pagos simulado implementado para demostración del flujo completo de transacciones en la plataforma sin necesidad de integrar pasarelas de pago reales como Stripe o PayPal.

---

## ✅ Características Implementadas

### 🔹 **Modelo de Datos**
- Tabla `payments` con toda la información de transacciones
- Estados: `pending`, `processing`, `completed`, `failed`, `refunded`
- Comisión de plataforma del 10% (configurable)
- Métodos de pago: `card`, `wallet`, `bank_transfer`
- ID de transacción único generado automáticamente

### 🔹 **Funcionalidades**
1. **Checkout/Pasarela de Pago**
   - Página de pago con selección de método
   - Simulación de datos de tarjeta
   - Resumen del pedido
   - Desglose de tarifas (precio + comisión)

2. **Procesamiento de Pago**
   - 90% de probabilidad de éxito (simulado)
   - 10% de probabilidad de fallo aleatorio
   - Delay de 1 segundo para simular procesamiento
   - Generación automática de transaction_id

3. **Historial de Pagos**
   - Vista diferenciada para clientes y profesionales
   - Clientes ven: Total gastado, pagos completados, pendientes
   - Profesionales ven: Total ganado, comisiones plataforma, pendientes
   - Filtrado y paginación

4. **Confirmación de Pago**
   - Página de éxito con resumen completo
   - ID de transacción visible
   - Información del servicio y profesional
   - Botones para descargar recibo o ver reserva

5. **Recibo de Pago**
   - Vista detallada tipo factura
   - Imprimible (CSS @media print)
   - Información completa de la transacción
   - Desglose de tarifas

6. **Reembolsos**
   - Solicitud de reembolso para reservas canceladas
   - Cambio de estado a `refunded`
   - Registro de notas de reembolso

---

## 🗂️ Estructura de Archivos

```
app/
├── Models/
│   └── Payment.php              # Modelo con relaciones y métodos auxiliares
├── Http/
    └── Controllers/
        └── PaymentController.php # Lógica de pagos

database/
├── migrations/
│   └── 2025_11_11_203005_create_payments_table.php
└── seeders/
    └── PaymentSeeder.php        # Genera pagos para reservas existentes

resources/
└── views/
    └── payments/
        ├── index.blade.php       # Historial de pagos
        ├── checkout.blade.php    # Pasarela de pago
        ├── confirmation.blade.php # Confirmación exitosa
        └── receipt.blade.php     # Recibo descargable

routes/
└── web.php                       # Rutas de pagos
```

---

## 📊 Esquema de Base de Datos

### Tabla: `payments`

| Campo               | Tipo        | Descripción                          |
|---------------------|-------------|--------------------------------------|
| id                  | BIGINT      | ID único del pago                    |
| booking_id          | BIGINT      | FK → bookings.id                     |
| user_id             | BIGINT      | FK → users.id (cliente)              |
| professional_id     | BIGINT      | FK → users.id (profesional)          |
| amount              | DECIMAL     | Monto total pagado                   |
| platform_fee        | DECIMAL     | Comisión de plataforma (10%)         |
| professional_amount | DECIMAL     | Monto que recibe el profesional      |
| payment_method      | STRING      | card, wallet, bank_transfer          |
| transaction_id      | STRING      | ID único de transacción              |
| status              | ENUM        | pending, processing, completed, failed, refunded |
| notes               | TEXT        | Notas adicionales                    |
| paid_at             | TIMESTAMP   | Fecha de pago completado             |
| created_at          | TIMESTAMP   | Fecha de creación                    |
| updated_at          | TIMESTAMP   | Fecha de actualización               |

**Relaciones:**
- `payments.booking_id` → `bookings.id` (CASCADE)
- `payments.user_id` → `users.id` (CASCADE)
- `payments.professional_id` → `users.id` (CASCADE)

---

## 🔗 Rutas Implementadas

```php
// Historial de pagos
GET  /payments                              → index()

// Checkout
GET  /bookings/{booking}/checkout           → checkout()

// Procesar pago
POST /payments                              → process()

// Confirmación
GET  /payments/{payment}/confirmation       → confirmation()

// Recibo
GET  /payments/{payment}/receipt            → receipt()

// Reembolso
POST /payments/{payment}/refund             → refund()
```

---

## 🔄 Flujo Completo de Pago

```
1. Cliente reserva servicio → Booking creado (status: pending)
                                        ↓
2. Profesional acepta reserva → Booking (status: accepted)
                                        ↓
3. Cliente ve botón "Pagar Servicio" en detalle de reserva
                                        ↓
4. Cliente accede a /bookings/{id}/checkout
                                        ↓
5. Cliente selecciona método de pago y confirma
                                        ↓
6. POST /payments → Se procesa el pago (simulación 90% éxito)
                                        ↓
    ├─ ÉXITO → Payment (status: completed)
    │          → Redirect a /payments/{payment}/confirmation
    │          → Envía email (opcional)
    │
    └─ FALLO → Payment (status: failed)
               → Redirect a reserva con mensaje de error
                                        ↓
7. Cliente puede ver recibo en /payments/{payment}/receipt
8. Cliente puede ver historial en /payments
```

---

## 💡 Métodos Importantes del Modelo

```php
// Verificación de estados
$payment->isPending()
$payment->isProcessing()
$payment->isCompleted()
$payment->isFailed()
$payment->isRefunded()

// Cambio de estados
$payment->markAsCompleted()
$payment->markAsFailed($reason)
$payment->markAsRefunded()

// Cálculos
Payment::generateTransactionId()
Payment::calculatePlatformFee($amount, $percentage = 10)
Payment::calculateProfessionalAmount($amount, $platformFee)

// Scopes
Payment::completed()->get()
Payment::pending()->get()
Payment::byUser($userId)->get()
Payment::byProfessional($professionalId)->get()
```

---

## 📈 Estadísticas Disponibles

### Para Clientes:
- Total gastado en servicios
- Número de pagos completados
- Monto pendiente de pago

### Para Profesionales:
- Total ganado (después de comisiones)
- Monto pendiente de recibir
- Total en comisiones pagadas a la plataforma
- Número de transacciones completadas

---

## 🎨 Características de UI

1. **Checkout Page**
   - Diseño moderno tipo Stripe/PayPal
   - Selección visual de métodos de pago
   - Resumen del pedido en sidebar sticky
   - Desglose de tarifas transparente
   - Términos y condiciones

2. **Confirmación Page**
   - Animación de éxito
   - ID de transacción destacado
   - Información completa del servicio
   - Próximos pasos claros

3. **Historial**
   - Tabla responsiva con información clave
   - Filtros por estado
   - Enlaces rápidos a recibo y reserva
   - Estadísticas en cards superiores

4. **Recibo**
   - Formato tipo factura profesional
   - Imprimible con CSS especial
   - Información completa de la transacción
   - QR code (opcional, futuro)

---

## 🔐 Seguridad y Validaciones

✅ Verificación de propietario de la reserva
✅ Validación de estado de reserva (solo aceptadas)
✅ Protección contra doble pago
✅ Verificación de permisos en todas las rutas
✅ Uso de transacciones DB para integridad
✅ Sanitización de inputs

---

## 🚀 Mejoras Futuras (para integración real)

### Integración con Stripe:
```php
// En lugar de simulación, usar Stripe API
$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

$intent = $stripe->paymentIntents->create([
    'amount' => $amount * 100, // En centavos
    'currency' => 'eur',
    'payment_method_types' => ['card'],
    'metadata' => [
        'booking_id' => $booking->id,
    ],
]);
```

### Webhooks:
- Recibir notificaciones de pago completado
- Procesar reembolsos automáticos
- Actualizar estados en tiempo real

### Pagos Diferidos:
- Pagar después del servicio completado
- Retención de fondos por seguridad
- Liberación automática tras X días

### Suscripciones:
- Planes premium para profesionales
- Comisiones reducidas
- Destacados en búsquedas

---

## 📊 Datos de Prueba

Al ejecutar el seeder, se generan:
- ✅ 6 pagos simulados
- ✅ 4 completados
- ✅ 2 pendientes
- ✅ Total recaudado: ~300€
- ✅ Comisiones plataforma: ~30€

---

## 🧪 Testing Manual

### Como Cliente:
1. Inicia sesión: `roberto@clientes.com` / `password`
2. Ve a "Mis Reservas"
3. Selecciona una reserva aceptada
4. Click en "Pagar Servicio"
5. Selecciona método de pago
6. Confirma pago
7. Ve la confirmación
8. Descarga recibo

### Como Profesional:
1. Inicia sesión: `carlos@profesionales.com` / `password`
2. Ve a "Mis Ingresos"
3. Revisa estadísticas
4. Ve historial de pagos recibidos
5. Descarga recibos de tus servicios

---

## 📝 Notas Importantes

⚠️ **Este es un sistema SIMULADO** para demostración. Los pagos no son reales.

⚠️ **Probabilidad de fallo:** 10% aleatorio para simular fallos reales de pagos.

⚠️ **Comisión:** La plataforma toma 10% de cada transacción (configurable).

⚠️ **Reembolsos:** Solo disponibles para reservas canceladas con pago completado.

---

## 🎓 Aprendizajes Implementados

- ✅ Modelo de datos para transacciones financieras
- ✅ Flujo completo de checkout
- ✅ Estados de pago y máquina de estados
- ✅ Cálculo automático de comisiones
- ✅ Generación de recibos/facturas
- ✅ Historial de transacciones
- ✅ UI/UX moderna para pagos
- ✅ Manejo de errores en transacciones
- ✅ Uso de DB transactions para integridad

---

**Fecha de implementación:** 11 de noviembre de 2025
**Versión:** 1.0.0
**Estado:** ✅ Completo y funcional
