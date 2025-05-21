<?php
require_once './src/lib/reports/getConsumoAlimentos.php';

$fechaInicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$fechaFin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
$reporte = getConsumoAlimentos($fechaInicio, $fechaFin);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Consumo de Alimentos - Granja Ganadera</title>
    <link rel="stylesheet" href="../../src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Reporte de Consumo de Alimentos <span>Por Producto y Animal/Corral</span></div>
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
                    <th>Producto</th>
                    <th>Corral</th>
                    <th>Animal</th>
                    <th>Especie</th>
                    <th>Total Cantidad</th>
                    <th>Última Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reporte)): ?>
                    <tr><td colspan="6">No hay datos disponibles.</td></tr>
                <?php else: ?>
                    <?php foreach ($reporte as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_corral'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['codigo_animal'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre_especie'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['total_cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($row['ultima_fecha']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>