<?php
require_once './src/lib/reports/getExitoReproductivo.php';

$reporte = getExitoReproductivo();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Отчет об успехе воспроизводства - Reporte de Éxito Reproductivo</title>
    <link rel="stylesheet" href="../../src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Отчет об успехе воспроизводства (Reporte de Éxito Reproductivo) <span>По виду и породе (Por Especie y Raza)</span></div>
        <a href="/" class="button">Вернуться (Volver)</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Вид (Especie)</th>
                    <th>Порода (Raza)</th>
                    <th>Всего беременностей (Total Gestaciones)</th>
                    <th>Успешные роды (Partos Exitosos)</th>
                    <th>Среднее количество потомства (Promedio de Crías)</th>
                    <th>Состояние (Estado)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reporte)): ?>
                    <tr><td colspan="6">Нет данных (No hay datos disponibles).</td></tr>
                <?php else: ?>
                    <?php foreach ($reporte as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre_especie'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_raza'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['total_gestaciones'] ?? '0'); ?></td>
                            <td><?php echo htmlspecialchars($row['partos_exitosos'] ?? '0'); ?></td>
                            <td><?php echo htmlspecialchars($row['promedio_crias'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['estado_gestacion'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>