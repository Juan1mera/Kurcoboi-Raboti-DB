<?php
require_once './src/lib/reports/getBalanceFinanciero.php';

$fechaInicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$fechaFin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
$reporte = getBalanceFinanciero($fechaInicio, $fechaFin);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Финансовый отчет - Reporte Financiero</title>
    <link rel="stylesheet" href="../../src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Финансовый отчет (Reporte Financiero) <span>Доходы и расходы (Ingresos y Gastos)</span></div>
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
                    <th>Тип (Tipo)</th>
                    <th>Общая сумма (Total Monto)</th>
                    <th>Клиент (Cliente)</th>
                    <th>Период (Período)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reporte)): ?>
                    <tr><td colspan="4">Нет данных (No hay datos disponibles).</td></tr>
                <?php else: ?>
                    <?php foreach ($reporte as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_monto']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_cliente'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['periodo']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>