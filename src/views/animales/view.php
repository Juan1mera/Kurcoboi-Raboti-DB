<?php
require_once __DIR__ . '/../../lib/db/Animales/Animales.php';
require_once __DIR__ . '/../../lib/db/Razas/Razas.php';
require_once __DIR__ . '/../../lib/db/Corrales/Corrales.php';

try {
    // Handle delete action
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id_animal = $_POST['delete_id'];
        if (deleteAnimal($id_animal)) {
            header("Location: view");
            exit;
        } else {
            throw new Exception("No se pudo eliminar el animal.");
        }
    }

    // Get filter parameters from GET
    $id = $_GET['id'] ?? null;
    $codigo = $_GET['codigo'] ?? null;
    $id_raza = $_GET['id_raza'] ?? null;
    $id_corral = $_GET['id_corral'] ?? null;
    $sexo = $_GET['sexo'] ?? null;
    $peso_min = $_GET['peso_min'] ?? null;
    $peso_max = $_GET['peso_max'] ?? null;
    $estado_salud = $_GET['estado_salud'] ?? null;
    $fecha_nacimiento_start = $_GET['fecha_nacimiento_start'] ?? null;
    $fecha_nacimiento_end = $_GET['fecha_nacimiento_end'] ?? null;
    $fecha_ingreso_start = $_GET['fecha_ingreso_start'] ?? null;
    $fecha_ingreso_end = $_GET['fecha_ingreso_end'] ?? null;

    // Fetch filtered animals
    $animales = getFilteredAnimals(
        $id,
        $codigo,
        $id_raza,
        $id_corral,
        $sexo,
        $peso_min,
        $peso_max,
        $estado_salud,
        $fecha_nacimiento_start,
        $fecha_nacimiento_end,
        $fecha_ingreso_start,
        $fecha_ingreso_end
    );

    // Fetch razas and corrales for dropdowns
    $razas = getRazas();
    $corrales = getCorrales();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Lista de Animales - Granja Ganadera</title>
        <link rel="stylesheet" href="/src/public/css/styles.css">
    </head>
    <body>
        <h1>Список животных - Lista de Animales</h1>
        <div class="form">
            <div class="title">Filtros <span>Filtra la lista de animales</span></div>
            <form method="GET" action="">
                <div class="field-container">
                    <label for="id">ID</label>
                    <input type="number" name="id" id="id" class="input" value="<?php echo htmlspecialchars($id ?? ''); ?>" placeholder="ID del animal">
                </div>
                <div class="field-container">
                    <label for="codigo">Código</label>
                    <input type="text" name="codigo" id="codigo" class="input" value="<?php echo htmlspecialchars($codigo ?? ''); ?>" placeholder="Código del animal">
                </div>
                <div class="field-container">
                    <label for="id_raza">Raza</label>
                    <select name="id_raza" id="id_raza" class="select">
                        <option value="">Todas las razas</option>
                        <?php foreach ($razas as $raza): ?>
                            <option value="<?php echo htmlspecialchars($raza['id_raza']); ?>" 
                                    <?php echo $id_raza == $raza['id_raza'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($raza['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field-container">
                    <label for="id_corral">Corral</label>
                    <select name="id_corral" id="id_corral" class="select">
                        <option value="">Todos los corrales</option>
                        <?php foreach ($corrales as $corral): ?>
                            <option value="<?php echo htmlspecialchars($corral['id_corral']); ?>" 
                                    <?php echo $id_corral == $corral['id_corral'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($corral['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field-container">
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" class="select">
                        <option value="">Ambos</option>
                        <option value="Macho" <?php echo $sexo == 'Macho' ? 'selected' : ''; ?>>Macho</option>
                        <option value="Hembra" <?php echo $sexo == 'Hembra' ? 'selected' : ''; ?>>Hembra</option>
                    </select>
                </div>
                <div class="field-container">
                    <label>Peso (kg)</label>
                    <input type="number" name="peso_min" id="peso_min" class="input" value="<?php echo htmlspecialchars($peso_min ?? ''); ?>" placeholder="Peso mínimo" step="0.01">
                    <input type="number" name="peso_max" id="peso_max" class="input" value="<?php echo htmlspecialchars($peso_max ?? ''); ?>" placeholder="Peso máximo" step="0.01">
                </div>
                <div class="field-container">
                    <label for="estado_salud">Estado de Salud</label>
                    <select name="estado_salud" id="estado_salud" class="select">
                        <option value="">Todos los estados</option>
                        <option value="Sano" <?php echo $estado_salud == 'Sano' ? 'selected' : ''; ?>>Sano</option>
                        <option value="En tratamiento" <?php echo $estado_salud == 'En tratamiento' ? 'selected' : ''; ?>>En tratamiento</option>
                        <option value="Crítico" <?php echo $estado_salud == 'Crítico' ? 'selected' : ''; ?>>Crítico</option>
                    </select>
                </div>
                <div class="field-container">
                    <label>Fecha de Nacimiento</label>
                    <!-- Empty date inputs send '' (empty string); handled as NULL in getFilteredAnimals -->
                    <input type="date" name="fecha_nacimiento_start" id="fecha_nacimiento_start" class="input" value="<?php echo htmlspecialchars($fecha_nacimiento_start ?? ''); ?>" placeholder="Desde">
                    <input type="date" name="fecha_nacimiento_end" id="fecha_nacimiento_end" class="input" value="<?php echo htmlspecialchars($fecha_nacimiento_end ?? ''); ?>" placeholder="Hasta">
                </div>
                <div class="field-container">
                    <label>Fecha de Ingreso</label>
                    <!-- Empty date inputs send '' (empty string); handled as NULL in getFilteredAnimals -->
                    <input type="date" name="fecha_ingreso_start" id="fecha_ingreso_start" class="input" value="<?php echo htmlspecialchars($fecha_ingreso_start ?? ''); ?>" placeholder="Desde">
                    <input type="date" name="fecha_ingreso_end" id="fecha_ingreso_end" class="input" value="<?php echo htmlspecialchars($fecha_ingreso_end ?? ''); ?>" placeholder="Hasta">
                </div>
                <div class="field-container">
                    <button type="submit" class="button-confirm">Aplicar Filtros</button>
                    <a href="view" class="button-confirm">Limpiar Filtros</a>
                </div>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Код (Código)</th>
                    <th>Порода (Raza)</th>
                    <th>Загон (Corral)</th>
                    <th>Пол (Sexo)</th>
                    <th>Вес (кг) (Peso (kg))</th>
                    <th>Состояние здоровья (Estado de Salud)</th>
                    <th>Дата рождения (Fecha de Nacimiento)</th>
                    <th>Дата поступления (Fecha de Ingreso)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($animales)): ?>
                    <tr>
                        <td colspan="10">Нет зарегистрированных животных (No hay animales registrados).</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($animales as $animal): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($animal['id_animal']); ?></td>
                            <td><?php echo htmlspecialchars($animal['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($animal['nombre_raza']); ?></td>
                            <td><?php echo htmlspecialchars($animal['nombre_corral']); ?></td>
                            <td><?php echo htmlspecialchars($animal['sexo']); ?></td>
                            <td><?php echo htmlspecialchars($animal['peso'] ?? 'N/A'); ?> Kg</td>
                            <td><?php echo htmlspecialchars($animal['estado_salud']); ?></td>
                            <td><?php echo htmlspecialchars($animal['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($animal['fecha_ingreso']); ?></td>
                            <td>
                                <a href="update.php?id_animal=<?php echo htmlspecialchars($animal['id_animal']); ?>" class="button-edit">Editar</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este animal?');">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($animal['id_animal']); ?>">
                                    <button type="submit" class="button-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>