<?php

require_once __DIR__ . '/db_connect.php';

function createTratamiento($id_animal, $descripcion, $fecha_inicio, $fecha_fin, $medicamento, $dosis) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Tratamientos (id_animal, descripcion, fecha_inicio, fecha_fin, medicamento, dosis) 
                VALUES (:id_animal, :descripcion, :fecha_inicio, :fecha_fin, :medicamento, :dosis)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_animal' => $id_animal,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin === '' ? null : $fecha_fin,
            ':medicamento' => $medicamento === '' ? null : $medicamento,
            ':dosis' => $dosis === '' ? null : $dosis
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear tratamiento: " . $e->getMessage());
    }
}

function getTratamientos() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT t.*, a.*, e.nombre AS nombre_especie
                FROM Tratamientos t
                INNER JOIN Animales a ON t.id_animal = a.id_animal
                INNER JOIN Especies e ON a.id_especie = e.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener tratamientos: " . $e->getMessage());
    }
}

function getTratamientoById($id_tratamiento) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT t.*, a.*, e.nombre AS nombre_especie
                FROM Tratamientos t
                INNER JOIN Animales a ON t.id_animal = a.id_animal
                INNER JOIN Especies e ON a.id_especie = e.id_especie
                WHERE t.id_tratamiento = :id_tratamiento";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_tratamiento' => $id_tratamiento]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener tratamiento: " . $e->getMessage());
    }
}

function updateTratamiento($id_tratamiento, $id_animal, $descripcion, $fecha_inicio, $fecha_fin, $medicamento, $dosis) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Tratamientos SET 
                id_animal = :id_animal, 
                descripcion = :descripcion, 
                fecha_inicio = :fecha_inicio, 
                fecha_fin = :fecha_fin, 
                medicamento = :medicamento, 
                dosis = :dosis 
                WHERE id_tratamiento = :id_tratamiento";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_tratamiento' => $id_tratamiento,
            ':id_animal' => $id_animal,
            ':descripcion' => $descripcion,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin === '' ? null : $fecha_fin,
            ':medicamento' => $medicamento === '' ? null : $medicamento,
            ':dosis' => $dosis === '' ? null : $dosis
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar tratamiento: " . $e->getMessage());
    }
}

function deleteTratamiento($id_tratamiento) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Tratamientos WHERE id_tratamiento = :id_tratamiento";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_tratamiento' => $id_tratamiento]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar tratamiento: " . $e->getMessage());
    }
}

?>