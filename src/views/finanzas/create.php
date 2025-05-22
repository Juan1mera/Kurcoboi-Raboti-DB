<?php
require_once __DIR__ . '/../../lib/db/Finanzas/Finanzas.php';
require_once __DIR__ . '/../../lib/db/Ventas/Ventas.php';
require_once __DIR__ . '/../../lib/db/Productos/Productos.php';

// Initialize variables for form handling
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tipo = $_POST['tipo'] ?? '';
        $monto = $_POST['monto'] ?? null;
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $id_venta = $_POST['id_venta'] ?? '';
        $id_producto = $_POST['id_producto'] ?? '';

        // Basic validation
        if ($tipo && $monto && $fecha) {
            $transaccionId = createTransaccion($tipo, $monto, $fecha, $descripcion, $id_venta, $id_producto);
            $success = true;
        } else {
            $error = 'Por favor, completa los campos obligatorios (Tipo, Monto, Fecha).';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch ventas and productos for dropdowns
$ventas = getVentas();
$productos = getProductos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Transacción</title>
    <link rel="stylesheet" href="/src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Crear Nueva Transacción <span>Ingresa los detalles de la transacción</span></div>
        
        <?php if ($success): ?>
            <p style="color: green;">Transacción creada exitosamente.</p>
        <?php elseif ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="field-container">
                <label for="tipo">Tipo</label>
                <select name="tipo" id="tipo" class="select" required>
                    <option value="">Selecciona el tipo</option>
                    <option value="Ingreso">Ingreso</option>
                    <option value="Gasto">Gasto</option>
                </select>
            </div>

            <div class="field-container">
                <label for="monto">Monto</label>
                <input type="number" name="monto" id="monto" class="input" placeholder="Monto" step="0.01" required>
            </div>

            <div class="field-container">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="input" required>
            </div>

            <div class="field-container">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="input" placeholder="Descripción"></textarea>
            </div>

            <div class="field-container">
                <label for="id_venta">Venta</label>
                <select name="id_venta" id="id_venta" class="select">
                    <option value="">Selecciona una venta (opcional)</option>
                    <?php foreach ($ventas as $venta): ?>
                        <option value="<?php echo htmlspecialchars($venta['id_venta']); ?>">
                            <?php echo htmlspecialchars($venta['id_venta'] . ' - ' . $venta['nombre_cliente']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field-container">
                <label for="id_producto">Producto</label>
                <select name="id_producto" id="id_producto" class="select">
                    <option value="">Selecciona un producto (opcional)</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                            <?php echo htmlspecialchars($producto['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="button-confirm">Crear Transacción</button>
        </form>
    </div>
</body>
</html>