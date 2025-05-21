<?php
require_once __DIR__ . './../../lib/db/Clientes/Clientes.php';

try {
    $clientes = getClientes();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Lista de Clientes - Granja Ganadera</title>
        <link rel="stylesheet" href="/src/public/css/styles.css">
    </head>
    <body>
        <h1>Список клиентов - Lista de Clientes</h1>
        <div class="form">
            <div class="title">Clientes <span>Lista de Clientes Registrados</span></div>
            <a href="create.php" class="button">Crear Cliente</a>
            <a href="/" class="button">Volver</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя (Nombre)</th>
                        <th>Телефон (Teléfono)</th>
                        <th>Адрес (Dirección)</th>
                        <th>Электронная почта (Email)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="5">Нет зарегистрированных клиентов (No hay clientes registrados).</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['id_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefono'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($cliente['direccion'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($cliente['email'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </body>
    </html>
    <?php
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>