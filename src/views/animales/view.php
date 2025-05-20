<?php

require_once __DIR__ . '/../../lib/db/Animales/Animales.php';

try {
    $animales = getAnimals();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Lista de Animales - Granja Ganadera</title>
        <link rel="stylesheet" href="/src/public/css/styles.css">
    </head>
    <body>
        <h1>Lista de Animales</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Raza</th>
                    <th>Corral</th>
                    <th>Sexo</th>
                    <th>Peso (kg)</th>
                    <th>Estado de Salud</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Fecha de Ingreso</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($animales)): ?>
                    <tr>
                        <td colspan="9">No hay animales registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($animales as $animal): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($animal['id_animal']); ?></td>
                            <td><?php echo htmlspecialchars($animal['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($animal['nombre_raza']); ?></td>
                            <td><?php echo htmlspecialchars($animal['nombre_corral']); ?></td>
                            <td><?php echo htmlspecialchars($animal['sexo']); ?></td>
                            <td><?php echo htmlspecialchars($animal['peso'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($animal['estado_salud']); ?></td>
                            <td><?php echo htmlspecialchars($animal['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($animal['fecha_ingreso']); ?></td>
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