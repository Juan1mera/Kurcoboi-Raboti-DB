<?php
require_once './src/lib/reports/getExitoReproductivo.php';

$reporte = getExitoReproductivo();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Éxito Reproductivo - Granja Ganadera</title>
    <link rel="stylesheet" href="../../src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Reporte de Éxito Reproductivo <span>Por Especie y Raza</span></div>
        <a href="/" class="button">Volver</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Total Gestaciones</th>
                    <th>Partos Exitosos</th>
                    <th>Promedio de Crías</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reporte)): ?>
                    <tr><td colspan="6">No hay datos disponibles.</td></tr>
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