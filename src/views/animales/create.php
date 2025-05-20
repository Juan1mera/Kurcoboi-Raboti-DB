<?php
require_once __DIR__ . '/../../lib/db/Animales/Animales.php';
require_once __DIR__ . '/../../lib/db/Razas/Razas.php';
require_once __DIR__ . '/../../lib/db/Corrales/Corrales.php';

// Initialize variables for form handling
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_raza = $_POST['id_raza'] ?? null;
        $id_corral = $_POST['id_corral'] ?? null;
        $codigo = $_POST['codigo'] ?? '';
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
        $peso = $_POST['peso'] ?? null;
        $estado_salud = $_POST['estado_salud'] ?? '';
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

        // Basic validation
        if ($id_raza && $id_corral && $codigo && $fecha_nacimiento && $sexo && $peso && $estado_salud && $fecha_ingreso) {
            $animalId = createAnimal($id_raza, $id_corral, $codigo, $fecha_nacimiento, $sexo, $peso, $estado_salud, $fecha_ingreso);
            $success = true;
        } else {
            $error = 'Por favor, completa todos los campos obligatorios.';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch breeds and corrals for dropdowns
$razas = getRazas();
$corrales = getCorrales();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Animal</title>
    <link rel="stylesheet" href="/src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Crear Nuevo Animal <span>Ingresa los detalles del animal</span></div>
        
        <?php if ($success): ?>
            <p style="color: green;">Animal creado exitosamente.</p>
        <?php elseif ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="field-container">
                <label for="id_raza">Raza</label>
                <select name="id_raza" id="id_raza" class="select" required>
                    <option value="">Selecciona una raza</option>
                    <?php foreach ($razas as $raza): ?>
                        <option value="<?php echo htmlspecialchars($raza['id_raza']); ?>">
                            <?php echo htmlspecialchars($raza['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field-container">
                <label for="id_corral">Corral</label>
                <select name="id_corral" id="id_corral" class="select" required>
                    <option value="">Selecciona un corral</option>
                    <?php foreach ($corrales as $corral): ?>
                        <option value="<?php echo htmlspecialchars($corral['id_corral']); ?>">
                            <?php echo htmlspecialchars($corral['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field-container">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" id="codigo" class="input" placeholder="Código del animal" required>
            </div>

            <div class="field-container">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                |<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="input" required>
            </div>

            <div class="field-container">
                <label for="sexo">Sexo</label>
                <select name="sexo" id="sexo" class="select" required>
                    <option value="">Selecciona el sexo</option>
                    <option value="Macho">Macho</option>
                    <option value="Hembra">Hembra</option>
                </select>
            </div>

            <div class="field-container">
                <label for="peso">Peso (kg)</label>
                <input type="number" name="peso" id="peso" class="input" placeholder="Peso (kg)" step="0.01" required>
            </div>

            <div class="field-container">
                <label for="estado_salud">Estado de Salud</label>
                <input type="text" name="estado_salud" id="estado_salud" class="input" placeholder="Estado de salud" required>
            </div>

            <div class="field-container">
                <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="input" required>
            </div>

            <button type="submit" class="button-confirm">Crear Animal</button>
        </form>
    </div>
</body>
</html>