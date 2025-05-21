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
        <h1>Список животных - Lista de Animales</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Код (Código)</th>
                    <th>Порода (Raza)</th>
                    <th>Загон (Corral)</th>
                    <th>Пол (Sexo)</th>
                    <th>Вес (кг) (Peso (kg))</th>
                    <th>Состояние здоровья (Estado de Salud)</th>
                    <th>Дата рождения (Fecha de Nacimiento)</th>
                    <th>Дата поступления (Fecha de Ingreso)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($animales)): ?>
                    <tr>
                        <td colspan="9">Нет зарегистрированных животных (No hay animales registrados).</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($animales as $animal): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($animal['id_animal']); ?></td>
                            <td><?php echo htmlspecialchars($animal['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($animal['nombre_raza']); ?></td>
                            <td><?php echo htmlspecialchars($animal['nombre_corral']); ?></td>
                            <td><?php echo htmlspecialchars($animal['sexo']); ?></td>
                            <td><?php echo htmlspecialchars($animal['peso'] ?? 'N/A'); ?> Kg</td>
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