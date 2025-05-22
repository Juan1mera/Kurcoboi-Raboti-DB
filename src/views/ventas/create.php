<?php
require_once __DIR__ . '/../../lib/db/Ventas/Ventas.php';
require_once __DIR__ . '/../../lib/db/Clientes/Clientes.php';
require_once __DIR__ . '/../../lib/db/Productos_Inventario/Productos_Inventario.php';
require_once __DIR__ . '/../../lib/db/Detalle_Ventas/Detalle_Ventas.php';
require_once __DIR__ . '/../../lib/db/Finanzas/Finanzas.php';

// Initialize variables for form handling
$success = false;
$error = '';
$debug_post = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_cliente = $_POST['id_cliente'] ?? null;
        $fecha = $_POST['fecha'] ?? '';
        $estado = $_POST['estado'] ?? '';
        $monto_total = $_POST['monto_total'] ?? null;
        $detalles = $_POST['detalles'] ?? [];

        // Debug: Capture POST data for display and logging
        $debug_post = '<pre>POST Data: ' . htmlspecialchars(print_r($_POST, true)) . '</pre>';
        error_log('POST detalles: ' . print_r($detalles, true));

        // Validate main sale fields
        if ($id_cliente && $fecha && $estado && $monto_total) {
            // Validate and filter detalles
            $valid_detalles = [];
            foreach ($detalles as $index => $detalle) {
                if (
                    isset($detalle['tipo_producto'], $detalle['cantidad'], $detalle['precio_unitario']) &&
                    is_string($detalle['tipo_producto']) && trim($detalle['tipo_producto']) !== '' &&
                    is_numeric($detalle['cantidad']) && $detalle['cantidad'] > 0 &&
                    is_numeric($detalle['precio_unitario']) && $detalle['precio_unitario'] >= 0
                ) {
                    $valid_detalles[] = $detalle;
                } else {
                    error_log("Invalid detalle at index $index: " . print_r($detalle, true));
                }
            }

            // Ensure at least one valid detail
            if (!empty($valid_detalles)) {
                // Create venta
                $ventaId = createVenta($id_cliente, $fecha, $estado, $monto_total);
                error_log("createVenta($ventaId) result: success");

                // Create detalles
                foreach ($valid_detalles as $detalle) {
                    $detalleId = createDetalleVenta(
                        $ventaId,
                        $detalle['id_producto'] ?? '',
                        $detalle['tipo_producto'],
                        $detalle['cantidad'],
                        $detalle['precio_unitario']
                    );
                    error_log("createDetalleVenta($detalleId) result: success");
                }

                // Create Finanzas transaction
                $descripcion = "Venta de productos (ID: $ventaId)";
                $transaccionId = createTransaccion(
                    'Ingreso',
                    $monto_total,
                    $fecha,
                    $descripcion,
                    $ventaId,
                    null
                );
                error_log("createTransaccion($transaccionId) for venta $ventaId result: success");

                $success = true;
            } else {
                $error = 'Por favor, agrega al menos un detalle completo (Tipo Producto, Cantidad > 0, Precio Unitario ≥ 0).';
            }
        } else {
            $error = 'Por favor, completa todos los campos obligatorios de la venta.';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Exception in POST handler: " . $e->getMessage());
    }
}

// Fetch clientes and productos for dropdowns
$clientes = getClientes();
$productos = getProductosInventario();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Venta</title>
    <link rel="stylesheet" href="/src/public/css/styles.css">
    <script>
        let detalleIndex = 0;

        function addDetalle() {
            const table = document.getElementById('detalles-table').querySelector('tbody');
            const row = document.createElement('tr');
            row.className = 'detalle-item';
            row.innerHTML = `
                <td>
                    <select name="detalles[${detalleIndex}][id_producto]" class="select">
                        <option value="">Selecciona un producto (opcional)</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                                <?php echo htmlspecialchars($producto['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="detalles[${detalleIndex}][tipo_producto]" class="input" placeholder="Tipo Producto" required>
                </td>
                <td>
                    <input type="number" name="detalles[${detalleIndex}][cantidad]" class="input" placeholder="Cantidad" step="0.01" min="0.01" required>
                </td>
                <td>
                    <input type="number" name="detalles[${detalleIndex}][precio_unitario]" class="input" placeholder="Precio Unitario" step="0.01" min="0" required>
                </td>
                <td>
                    <button type="button" class="button-delete" onclick="this.parentElement.parentElement.remove(); toggleSubmitButton()">Eliminar</button>
                </td>
            `;
            table.appendChild(row);
            detalleIndex++;
            toggleSubmitButton();
        }

        // Client-side validation
        function validateForm(event) {
            const detalles = document.querySelectorAll('.detalle-item');
            if (detalles.length === 0) {
                alert('Por favor, agrega al menos un detalle de venta.');
                event.preventDefault();
                return false;
            }
            for (let detalle of detalles) {
                const tipo = detalle.querySelector('input[name$="[tipo_producto]"]').value;
                const cantidad = detalle.querySelector('input[name$="[cantidad]"]').value;
                const precio = detalle.querySelector('input[name$="[precio_unitario]"]').value;
                if (!tipo || !cantidad || cantidad <= 0 || !precio || precio < 0) {
                    alert('Por favor, completa todos los campos de los detalles (Tipo Producto, Cantidad > 0, Precio Unitario ≥ 0).');
                    event.preventDefault();
                    return false;
                }
            }
            console.log('Form data:', new FormData(document.querySelector('form')));
            return true;
        }

        // Enable/disable submit button
        function toggleSubmitButton() {
            const detalles = document.querySelectorAll('.detalle-item');
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = detalles.length === 0;
        }
    </script>
</head>
<body>
    <div class="form">
        <div class="title">Crear Nueva Venta <span>Ingresa los detalles de la venta</span></div>
        
        <?php if ($success): ?>
            <p style="color: green;">Venta creada exitosamente.</p>
        <?php elseif ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($debug_post): ?>
            <div style="background: #f0f0f0; padding: 10px; border: 1px solid #ccc; margin-bottom: 20px;">
                <h3>Debug: Datos enviados (POST)</h3>
                <?php echo $debug_post; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="return validateForm(event)">
            <div class="field-container">
                <label for="id_cliente">Cliente</label>
                <select name="id_cliente" id="id_cliente" class="select" required>
                    <option value="">Selecciona un cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo htmlspecialchars($cliente['id_cliente']); ?>">
                            <?php echo htmlspecialchars($cliente['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field-container">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="input" required>
            </div>

            <div class="field-container">
                <label for="estado">Estado</label>
                <select name="estado" id="estado" class="select" required>
                    <option value="">Selecciona el estado</option>
                    <option value="Completada">Completada</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Cancelada">Cancelada</option>
                </select>
            </div>

            <div class="field-container">
                <label for="monto_total">Monto Total</label>
                <input type="number" name="monto_total" id="monto_total" class="input" placeholder="Monto Total" step="0.01" min="0" required>
            </div>

            <div class="field-container">
                <label>Detalles de la Venta</label>
                <table id="detalles-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button type="button" class="button-confirm" onclick="addDetalle()">Agregar Detalle</button>
            </div>

            <button type="submit" class="button-confirm" disabled>Crear Venta</button>
        </form>
    </div>
</body>
</html>