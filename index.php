<?php
session_start();

// Enrutamiento
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim($request, '/');
$parts = explode('/', $request);
$viewPath = 'src/views';

if (empty($parts[0])) {
    // Página de bienvenida
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Sistema de Gestión - Granja Ganadera</title>
        <link rel="stylesheet" href="src/public/css/styles.css">
    </head>
    <body>
        <div class="form">
            <div class="title">Granja Ganadera <span>Sistema de Gestión</span></div>
            <p style="color: var(--font-color); font-size: 16px;">Bienvenido al sistema de gestión de la granja. Selecciona una opción:</p>
            <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center;">
                <a href="animales/view" class="button">Ver Animales</a>
                <a href="reports/balance_financiero" class="button">Reporte Financiero</a>
                <a href="reports/consumo_alimentos" class="button">Consumo de Alimentos</a>
                <a href="reports/estado_salud_animales" class="button">Estado de Salud</a>
                <a href="reports/exito_reproductivo" class="button">Éxito Reproductivo</a>
                <a href="reports/produccion_por_tipo_corral" class="button">Producción por Corral</a>
            </div>
        </div>
    </body>
    </html>
    <?php
} elseif (count($parts) >= 2) {
    // Cargar vista específica (por ejemplo, animales/view)
    $controller = $parts[0];
    $view = $parts[1];
    $viewFile = "$viewPath/$controller/$view.php";
    if (file_exists($viewFile)) {
        include $viewFile;
    } else {
        http_response_code(404);
        echo "Página no encontrada: $viewFile";
    }
} else {
    // Cargar vista por defecto del controlador (por ejemplo, animales/index)
    $controller = $parts[0];
    $viewFile = "$viewPath/$controller/index.php";
    if (file_exists($viewFile)) {
        include $viewFile;
    } else {
        http_response_code(404);
        echo "Página no encontrada: $viewFile";
    }
}
?>