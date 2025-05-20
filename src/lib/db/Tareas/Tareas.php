<?php

require_once __DIR__ . '/db_connect.php';

function createTarea($id_empleado, $tipo, $fecha, $descripcion, $estado, $id_animal, $id_corral) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Tareas (id_empleado, tipo, fecha, descripcion, estado, id_animal, id_corral) 
                VALUES (:id_empleado, :tipo, :fecha, :descripcion, :estado, :id_animal, :id_corral)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_empleado' => $id_empleado,
            ':tipo' => $tipo,
            ':fecha' => $fecha,
            ':descripcion' => $descripcion === '' ? null : $descripcion,
            ':estado' => $estado,
            ':id_animal' => $id_animal === '' ? null : $id_animal,
            ':id_corral' => $id_corral === '' ? null : $id_corral
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear tarea: " . $e->getMessage());
    }
}

function getTareas() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT t.*, e.nombre AS nombre_empleado, a.*, c.*, es.nombre AS nombre_especie
                FROM Tareas t
                LEFT JOIN Empleados e ON t.id_empleado = e.id_empleado
                LEFT JOIN Animales a ON t.id_animal = a.id_animal
                LEFT JOIN Corrales c ON t.id_corral = c.id_corral
                LEFT JOIN Especies es ON a.id_especie = es.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener tareas: " . $e->getMessage());
    }
}

function getTareaById($id_tarea) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT t.*, e.nombre AS nombre_empleado, a.*, c.*, es.nombre AS nombre_especie
                FROM Tareas t
                LEFT JOIN Empleados e ON t.id_empleado = e.id_empleado
                LEFT JOIN Animales a ON t.id_animal = a.id_animal
                LEFT JOIN Corrales c ON t.id_corral = c.id_corral
                LEFT JOIN Especies es ON a.id_especie = es.id_especie
                WHERE t.id_tarea = :id_tarea";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_tarea' => $id_tarea]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener tarea: " . $e->getMessage());
    }
}

function updateTarea($id_tarea, $id_empleado, $tipo, $fecha, $descripcion, $estado, $id_animal, $id_corral) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Tareas SET 
                id_empleado = :id_empleado, 
                tipo = :tipo, 
                fecha = :fecha, 
                descripcion = :descripcion, 
                estado = :estado, 
                id_animal = :id_animal, 
                id_corral = :id_corral 
                WHERE id_tarea = :id_tarea";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_tarea' => $id_tarea,
            ':id_empleado' => $id_empleado,
            ':tipo' => $tipo,
            ':fecha' => $fecha,
            ':descripcion' => $descripcion === '' ? null : $descripcion,
            ':estado' => $estado,
            ':id_animal' => $id_animal === '' ? null : $id_animal,
            ':id_corral' => $id_corral === '' ? null : $id_corral
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar tarea: " . $e->getMessage());
    }
}

function deleteTarea($id_tarea) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Tareas WHERE id_tarea = :id_tarea";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_tarea' => $id_tarea]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar tarea: " . $e->getMessage());
    }
}

?>