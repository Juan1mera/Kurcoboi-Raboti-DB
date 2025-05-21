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
    <title>Reporte de Producción por Corral - Granja Ganadera</title>
    <link rel="stylesheet" href="../../src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Reporte de Producción <span>Por Tipo de Producto y Corral</span></div>
        <form method="POST" action="">
            <div class="field-container">
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="input" value="<?php echo $fechaInicio; ?>">
            </div>
            <div class="field-container">
                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="input" value="<?php echo $fechaFin; ?>">
            </div>
            <button type="submit" class="button-confirm">Filtrar</button>
        </form>
        <a href="/" class="button">Volver</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tipo de Producto</th>
                    <th>Corral</th>
                    <th>Especie</th>
                    <th>Total Cantidad</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reporte)): ?>
                    <tr><td colspan="5">No hay datos disponibles.</td></tr>
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