<?php
require_once __DIR__ . '/../../lib/db/Ventas/Ventas.php';
require_once __DIR__ . './../../lib/db/Detalle_Ventas/Detalle_Ventas.php';

try {
    // Handle delete action
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id_venta = $_POST['delete_id'];
        // Delete associated detalles first
        deleteDetalleVenta($id_venta);
        if (deleteVenta($id_venta)) {
            header("Location: view");
            exit;
        } else {
            throw new Exception("No se pudo eliminar la venta.");
        }
    }

    $ventas = getVentas();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Lista de Ventas - Granja Ganadera</title>
        <link rel="stylesheet" href="/src/public/css/styles.css">
    </head>
    <body>
        <h1>Lista de Ventas</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Monto Total</th>
                    <th>Detalles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ventas)): ?>
                    <tr>
                        <td colspan="7">No hay ventas registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($venta['id_venta']); ?></td>
                            <td><?php echo htmlspecialchars($venta['nombre_cliente']); ?></td>
                            <td><?php echo htmlspecialchars($venta['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($venta['estado']); ?></td>
                            <td><?php echo htmlspecialchars($venta['monto_total']); ?></td>
                            <td>
                                <?php
                                $detalles = getDetallesVenta($venta['id_venta']);
                                if (!empty($detalles)) {
                                    echo "<ul>";
                                    foreach ($detalles as $detalle) {
                                        echo "<li>" . htmlspecialchars($detalle['nombre_producto'] ?? $detalle['tipo_producto']) . 
                                             ": " . htmlspecialchars($detalle['cantidad']) . " @ $" . 
                                             htmlspecialchars($detalle['precio_unitario']) . "</li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "Sin detalles";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="update?id_venta=<?php echo htmlspecialchars($venta['id_venta']); ?>" class="button-edit">Editar</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta venta y sus detalles?');">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($venta['id_venta']); ?>">
                                    <button type="submit" class="button-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>