<?php
session_start();
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim($request, '/');
$parts = explode('/', $request);
$viewPath = 'src/views';
if (empty($parts[0])) {
    echo "Bienvenido al Sistema de Gestión de la Granja Ganadera";
} elseif (count($parts) >= 2) {
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