<?php
require_once __DIR__ . '/../../lib/db/Ventas/Ventas.php';
require_once __DIR__ . '/../../lib/db/Clientes/Clientes.php';
require_once __DIR__ . '/../../lib/db/Productos_Inventario/Productos_Inventario.php';
require_once __DIR__ . '/../../lib/db/Detalle_Ventas/Detalle_Ventas.php';

// Initialize variables for form handling
$success = false;
$error = '';
$debug_post = '';
$venta = null;

// Check if id_venta is provided
if (!isset($_GET['id_venta']) || !is_numeric($_GET['id_venta'])) {
    $error = 'ID de venta inválido.';
} else {
    try {
        $venta = getVentaById($_GET['id_venta']);
        if (!$venta) {
            $error = 'Venta no encontrada.';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    try {
        $id_venta = $_POST['id_venta'] ?? null;
        $id_cliente = $_POST['id_cliente'] ?? null;
        $fecha = $_POST['fecha'] ?? '';
        $estado = $_POST['estado'] ?? '';
        $monto_total = $_POST['monto_total'] ?? null;
        $detalles = $_POST['detalles'] ?? [];
        $delete_detalles = $_POST['delete_detalles'] ?? [];

        // Debug: Capture POST data
        $debug_post = '<pre>POST Data: ' . htmlspecialchars(print_r($_POST, true)) . '</pre>';
        error_log('POST detalles: ' . print_r($detalles, true));

        // Basic validation
        if ($id_venta && $id_cliente && $fecha && $estado && $monto_total !== null) {
            // Update venta (log result)
            $venta_updated = updateVenta($id_venta, $id_cliente, $fecha, $estado, $monto_total);
            error_log("updateVenta($id_venta) result: " . ($venta_updated ? 'success' : 'no changes'));

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

            // Process details even if venta update didn't change rows
            $details_updated = false;
            if (!empty($valid_detalles) || !empty($delete_detalles)) {
                // Delete selected detalles
                foreach ($delete_detalles as $id_detalle) {
                    $delete_result = deleteDetalleVenta($id_detalle);
                    error_log("deleteDetalleVenta($id_detalle) result: " . ($delete_result ? 'success' : 'failed'));
                    if ($delete_result) $details_updated = true;
                }
                // Update or create detalles
                foreach ($valid_detalles as $detalle) {
                    if (!empty($detalle['id_detalle'])) {
                        $update_result = updateDetalleVenta(
                            $detalle['id_detalle'],
                            $id_venta,
                            $detalle['id_producto'] ?? '',
                            $detalle['tipo_producto'],
                            $detalle['cantidad'],
                            $detalle['precio_unitario']
                        );
                        error_log("updateDetalleVenta({$detalle['id_detalle']}) result: " . ($update_result ? 'success' : 'no changes'));
                        if ($update_result) $details_updated = true;
                    } else {
                        $new_id = createDetalleVenta(
                            $id_venta,
                            $detalle['id_producto'] ?? '',
                            $detalle['tipo_producto'],
                            $detalle['cantidad'],
                            $detalle['precio_unitario']
                        );
                        error_log("createDetalleVenta(new_id=$new_id) result: success");
                        $details_updated = true;
                    }
                }
            }

            if ($venta_updated || $details_updated) {
                $success = true;
                $venta = getVentaById($id_venta);
            } else {
                $error = 'No se realizaron cambios en la venta ni en los detalles.';
            }
        } else {
            $error = 'Por favor, completa todos los campos obligatorios.';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Exception in POST handler: " . $e->getMessage());
    }
}

// Fetch clientes, productos, and detalles
$clientes = getClientes();
$productos = getProductosInventario();
$detalles = $venta ? getDetallesVenta($venta['id_venta']) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta</title>
    <link rel="stylesheet" href="/src/public/css/styles.css">
    <script>
        let detalleIndex = <?php echo count($detalles); ?>;

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
            for (let detalle of detalles) {
                const tipo = detalle.querySelector('input[name$="[tipo_producto]"]')?.value;
                const cantidad = detalle.querySelector('input[name$="[cantidad]"]')?.value;
                const precio = detalle.querySelector('input[name$="[precio_unitario]"]')?.value;
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
        <div class="title">Editar Venta <span>Actualiza los detalles de la venta</span></div>
        
        <?php if ($success): ?>
            <p style="color: green;">Venta actualizada exitosamente.</p>
        <?php elseif ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($debug_post): ?>
            <div style="background: #f0f0f0; padding: 10px; border: 1px solid #ccc; margin-bottom: 20px;">
                <h3>Debug: Datos enviados (POST)</h3>
                <?php echo $debug_post; ?>
            </div>
        <?php endif; ?>

        <?php if ($venta): ?>
            <form method="POST" action="" onsubmit="return validateForm(event)">
                <input type="hidden" name="id_venta" value="<?php echo htmlspecialchars($venta['id_venta']); ?>">
                
                <div class="field-container">
                    <label for="id_cliente">Cliente</label>
                    <select name="id_cliente" id="id_cliente" class="select" required>
                        <option value="">Selecciona un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo htmlspecialchars($cliente['id_cliente']); ?>" 
                                    <?php echo $cliente['id_cliente'] == $venta['id_cliente'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cliente['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-container">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="input" 
                           value="<?php echo htmlspecialchars($venta['fecha'] ?? ''); ?>" required>
                </div>

                <div class="field-container">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="select" required>
                        <option value="">Selecciona el estado</option>
                        <option value="Completada" <?php echo $venta['estado'] == 'Completada' ? 'selected' : ''; ?>>Completada</option>
                        <option value="Pendiente" <?php echo $venta['estado'] == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="Cancelada" <?php echo $venta['estado'] == 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>

                <div class="field-container">
                    <label for="monto_total">Monto Total</label>
                    <input type="number" name="monto_total" id="monto_total" class="input" 
                           value="<?php echo htmlspecialchars($venta['monto_total'] ?? ''); ?>" step="0.01" min="0" required>
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
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles as $index => $detalle): ?>
                                <tr class="detalle-item">
                                    <td>
                                        <select name="detalles[<?php echo $index; ?>][id_producto]" class="select">
                                            <option value="">Selecciona un producto (opcional)</option>
                                            <?php foreach ($productos as $producto): ?>
                                                <option value="<?php echo htmlspecialchars($producto['id_producto']); ?>" 
                                                        <?php echo $producto['id_producto'] == $detalle['id_producto'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="detalles[<?php echo $index; ?>][tipo_producto]" class="input" 
                                               value="<?php echo htmlspecialchars($detalle['tipo_producto']); ?>" required>
                                    </td>
                                    <td>
                                        <input type="number" name="detalles[<?php echo $index; ?>][cantidad]" class="input" 
                                               value="<?php echo htmlspecialchars($detalle['cantidad']); ?>" step="0.01" min="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" name="detalles[<?php echo $index; ?>][precio_unitario]" class="input" 
                                               value="<?php echo htmlspecialchars($detalle['precio_unitario']); ?>" step="0.01" min="0" required>
                                    </td>
                                    <td>
                                        <label><input type="checkbox" name="delete_detalles[]" value="<?php echo htmlspecialchars($detalle['id_detalle']); ?>"> Eliminar</label>
                                        <input type="hidden" name="detalles[<?php echo $index; ?>][id_detalle]" value="<?php echo htmlspecialchars($detalle['id_detalle']); ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="button-confirm" onclick="addDetalle()">Agregar Detalle</button>
                </div>

                <button type="submit" class="button-confirm">Actualizar Venta</button>
            </form>
        <?php else: ?>
            <p>No se puede editar la venta debido a un error.</p>
        <?php endif; ?>
    </div>
</body>
</html>