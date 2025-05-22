<?php
require_once __DIR__ . '/../../lib/db/Finanzas/Finanzas.php';
require_once __DIR__ . '/../../lib/db/Ventas/Ventas.php';
require_once __DIR__ . '/../../lib/db/Productos/Productos.php';

try {
    // Handle delete action
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id_transaccion = $_POST['delete_id'];
        if (deleteTransaccion($id_transaccion)) {
            header("Location: view.php");
            exit;
        } else {
            throw new Exception("No se pudo eliminar la transacción.");
        }
    }

    $transacciones = getTransacciones();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Lista de Transacciones - Granja Ganadera</title>
        <link rel="stylesheet" href="/src/public/css/styles.css">
    </head>
    <body>
        <h1>Lista de Transacciones</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>ID Venta</th>
                    <th>ID Producto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transacciones)): ?>
                    <tr>
                        <td colspan="8">No hay transacciones registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transacciones as $transaccion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaccion['id_transaccion']); ?></td>
                            <td><?php echo htmlspecialchars($transaccion['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($transaccion['monto']); ?></td>
                            <td><?php echo htmlspecialchars($transaccion['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($transaccion['descripcion'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($transaccion['id_venta'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($transaccion['id_producto'] ?? 'N/A'); ?></td>
                            <td>
                                <a href="update.php?id_transaccion=<?php echo htmlspecialchars($transaccion['id_transaccion']); ?>" class="button-edit">Editar</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta transacción?');">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($transaccion['id_transaccion']); ?>">
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