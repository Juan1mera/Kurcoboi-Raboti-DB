<?php

require_once __DIR__ . '/../../lib/db/Animales/Animales.php';

try {
    // Handle delete action
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id_animal = $_POST['delete_id'];
        if (deleteAnimal($id_animal)) {
            header("Location: view.php");
            exit;
        } else {
            throw new Exception("No se pudo eliminar el animal.");
        }
    }

    $animales = getAnimals();
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
                                <a href="update?id_animal=<?php echo htmlspecialchars($animal['id_animal']); ?>" class="button-edit">Editar</a>
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