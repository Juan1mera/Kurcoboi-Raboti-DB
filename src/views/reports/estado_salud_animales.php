<?php
require_once './src/lib/reports/getEstadoSaludAnimales.php';

$reporte = getEstadoSaludAnimales();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Отчет о состоянии здоровья - Reporte de Estado de Salud</title>
    <link rel="stylesheet" href="../../src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Отчет о состоянии здоровья (Reporte de Estado de Salud) <span>По виду и породе (Por Especie y Raza)</span></div>
        <a href="/" class="button">Вернуться (Volver)</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Вид (Especie)</th>
                    <th>Порода (Raza)</th>
                    <th>Состояние здоровья (Estado de Salud)</th>
                    <th>Количество животных (Cantidad de Animales)</th>
                    <th>Процент (Porcentaje)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reporte)): ?>
                    <tr><td colspan="5">Нет данных (No hay datos disponibles).</td></tr>
                <?php else: ?>
                    <?php foreach ($reporte as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre_especie']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_raza']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado_salud']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidad_animales']); ?></td>
                            <td><?php echo htmlspecialchars($row['porcentaje']); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>