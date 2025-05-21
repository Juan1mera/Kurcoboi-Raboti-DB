<?php
require_once './src/lib/reports/getProduccionPorTipoCorral.php';

$fechaInicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$fechaFin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
$reporte = getProduccionPorTipoCorral($fechaInicio, $fechaFin);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Отчет о производстве - Reporte de Producción</title>
    <link rel="stylesheet" href="../../src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Отчет о производстве (Reporte de Producción) <span>По типу продукта и загону (Por Tipo de Producto y Corral)</span></div>
        <form method="POST" action="">
            <div class="field-container">
                <label for="fecha_inicio">Дата начала (Fecha Inicio):</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="input" value="<?php echo $fechaInicio; ?>">
            </div>
            <div class="field-container">
                <label for="fecha_fin">Дата окончания (Fecha Fin):</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="input" value="<?php echo $fechaFin; ?>">
            </div>
            <button type="submit" class="button-confirm">Фильтровать (Filtrar)</button>
        </form>
        <a href="/" class="button">Вернуться (Volver)</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Тип продукта (Tipo de Producto)</th>
                    <th>Загон (Corral)</th>
                    <th>Вид (Especie)</th>
                    <th>Общее количество (Total Cantidad)</th>
                    <th>Единица измерения (Unidad)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reporte)): ?>
                    <tr><td colspan="5">Нет данных (No hay datos disponibles).</td></tr>
                <?php else: ?>
                    <?php foreach ($reporte as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['tipo_producto']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_corral'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_especie'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['total_cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($row['unidad_medida']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>