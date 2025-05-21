<?php
require_once __DIR__ . '/../../lib/db/Clientes/Clientes.php';

// Initialize variables for form handling
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $email = $_POST['email'] ?? '';

        // Basic validation
        if ($nombre) {
            $clienteId = createCliente($nombre, $telefono, $direccion, $email);
            $success = true;
        } else {
            $error = 'Пожалуйста, заполните обязательное поле: Имя (Por favor, completa el campo obligatorio: Nombre).';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать Клиента (Crear Cliente)</title>
    <link rel="stylesheet" href="/src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Создать Нового Клиента (Crear Nuevo Cliente) <span>Введите данные клиента</span></div>
        
        <?php if ($success): ?>
            <p style="color: green;">Клиент успешно создан (Cliente creado exitosamente).</p>
        <?php elseif ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="field-container">
                <label for="nombre">Имя (Nombre)</label>
                <input type="text" name="nombre" id="nombre" class="input" placeholder="Имя клиента (Nombre del cliente)" required>
            </div>

            <div class="field-container">
                <label for="telefono">Телефон (Teléfono)</label>
                <input type="text" name="telefono" id="telefono" class="input" placeholder="Телефон клиента (Teléfono del cliente)">
            </div>

            <div class="field-container">
                <label for="direccion">Адрес (Dirección)</label>
                <input type="text" name="direccion" id="direccion" class="input" placeholder="Адрес клиента (Dirección del cliente)">
            </div>

            <div class="field-container">
                <label for="email">Электронная почта (Email)</label>
                <input type="email" name="email" id="email" class="input" placeholder="Электронная почта клиента (Email del cliente)">
            </div>

            <button type="submit" class="button-confirm">Создать Клиента (Crear Cliente)</button>
        </form>
        <a href="view" class="button">Вернуться к списку (Volver a la lista)</a>
    </div>
</body>
</html>