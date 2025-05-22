<?php
require_once __DIR__ . '/../../lib/db/Animales/Animales.php';
require_once __DIR__ . '/../../lib/db/Razas/Razas.php';
require_once __DIR__ . '/../../lib/db/Corrales/Corrales.php';

// Initialize variables for form handling
$success = false;
$error = '';
$animal = null;
$max_codigo_length = 10; // Adjust based on DESCRIBE Animales output (e.g., VARCHAR(10))

// Check if id_animal is provided
if (!isset($_GET['id_animal']) || !is_numeric($_GET['id_animal'])) {
    $error = 'ID de animal inválido.';
} else {
    try {
        $animal = getAnimalById($_GET['id_animal']);
        if (!$animal) {
            $error = 'Animal no encontrado o no está activo.';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    try {
        $id_animal = $_POST['id_animal'] ?? null;
        $id_raza = $_POST['id_raza'] ?? null;
        $id_corral = $_POST['id_corral'] ?? null;
        $codigo = $_POST['codigo'] ?? '';
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
        $peso = $_POST['peso'] ?? null;
        $estado_salud = $_POST['estado_salud'] ?? '';
        $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';

        // Validate inputs
        if ($id_animal && $id_raza && $id_corral && $codigo && $fecha_nacimiento && $sexo && $peso && $estado_salud && $fecha_ingreso) {
            // Validate codigo length
            if (strlen($codigo) > $max_codigo_length) {
                $error = "El código no debe exceder los $max_codigo_length caracteres.";
            } else {
                if (updateAnimal($id_animal, $id_raza, $id_corral, $codigo, $fecha_nacimiento, $sexo, $peso, $estado_salud, $fecha_ingreso)) {
                    $success = true;
                    // Refresh animal data
                    $animal = getAnimalById($id_animal);
                } else {
                    $error = 'No se realizaron cambios en el animal.';
                }
            }
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
    <title>Editar Animal</title>
    <link rel="stylesheet" href="/src/public/css/styles.css">
</head>
<body>
    <div class="form">
        <div class="title">Editar Animal <span>Actualiza los detalles del animal</span></div>
        
        <?php if ($success): ?>
            <p style="color: green;">Animal actualizado exitosamente.</p>
        <?php elseif ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($animal): ?>
            <form method="POST" action="">
                <input type="hidden" name="id_animal" value="<?php echo htmlspecialchars($animal['id_animal']); ?>">
                
                <div class="field-container">
                    <label for="id_raza">Raza</label>
                    <select name="id_raza" id="id_raza" class="select" required>
                        <option value="">Selecciona una raza</option>
                        <?php foreach ($razas as $raza): ?>
                            <option value="<?php echo htmlspecialchars($raza['id_raza']); ?>" 
                                    <?php echo $raza['id_raza'] == $animal['id_raza'] ? 'selected' : ''; ?>>
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
                            <option value="<?php echo htmlspecialchars($corral['id_corral']); ?>" 
                                    <?php echo $corral['id_corral'] == $animal['id_corral'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($corral['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-container">
                    <label for="codigo">Código</label>
                    <input type="text" name="codigo" id="codigo" class="input" 
                           value="<?php echo htmlspecialchars($animal['codigo'] ?? ''); ?>" 
                           maxlength="<?php echo $max_codigo_length; ?>" required>
                </div>

                <div class="field-container">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="input" 
                           value="<?php echo htmlspecialchars($animal['fecha_nacimiento'] ?? ''); ?>" required>
                </div>

                <div class="field-container">
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" class="select" required>
                        <option value="">Selecciona el sexo</option>
                        <option value="Macho" <?php echo $animal['sexo'] == 'Macho' ? 'selected' : ''; ?>>Macho</option>
                        <option value="Hembra" <?php echo $animal['sexo'] == 'Hembra' ? 'selected' : ''; ?>>Hembra</option>
                    </select>
                </div>

                <div class="field-container">
                    <label for="peso">Peso (kg)</label>
                    <input type="number" name="peso" id="peso" class="input" 
                           value="<?php echo htmlspecialchars($animal['peso'] ?? ''); ?>" step="0.01" required>
                </div>

                <div class="field-container">
                    <label for="estado_salud">Estado de Salud</label>
                    <input type="text" name="estado_salud" id="estado_salud" class="input" 
                           value="<?php echo htmlspecialchars($animal['estado_salud'] ?? ''); ?>" required>
                </div>

                <div class="field-container">
                    <label for="fecha_ingreso">Fecha de Ingreso</label>
                    <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="input" 
                           value="<?php echo htmlspecialchars($animal['fecha_ingreso'] ?? ''); ?>" required>
                </div>

                <button type="submit" class="button-confirm">Actualizar Animal</button>
            </form>
        <?php else: ?>
            <p>No se puede editar el animal debido a un error.</p>
        <?php endif; ?>
    </div>
</body>
</html>