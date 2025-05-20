<?php

require_once __DIR__ . '/db_connect.php';

function createVacuna($nombre, $id_especie, $descripcion, $dosis_requerida) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Vacunas (nombre, id_especie, descripcion, dosis_requerida) 
                VALUES (:nombre, :id_especie, :descripcion, :dosis_requerida)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':id_especie' => $id_especie,
            ':descripcion' => $descripcion === '' ? null : $descripcion,
            ':dosis_requerida' => $dosis_requerida
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear vacuna: " . $e->getMessage());
    }
}

function getVacunas() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT v.*, e.nombre AS nombre_especie
                FROM Vacunas v
                INNER JOIN Especies e ON v.id_especie = e.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener vacunas: " . $e->getMessage());
    }
}

function getVacunaById($id_vacuna) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT v.*, e.nombre AS nombre_especie
                FROM Vacunas v
                INNER JOIN Especies e ON v.id_especie = e.id_especie
                WHERE v.id_vacuna = :id_vacuna";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_vacuna' => $id_vacuna]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener vacuna: " . $e->getMessage());
    }
}

function updateVacuna($id_vacuna, $nombre, $id_especie, $descripcion, $dosis_requerida) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Vacunas SET 
                nombre = :nombre, 
                id_especie = :id_especie, 
                descripcion = :descripcion, 
                dosis_requerida = :dosis_requerida 
                WHERE id_vacuna = :id_vacuna";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_vacuna' => $id_vacuna,
            ':nombre' => $nombre,
            ':id_especie' => $id_especie,
            ':descripcion' => $descripcion === '' ? null : $descripcion,
            ':dosis_requerida' => $dosis_requerida
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar vacuna: " . $e->getMessage());
    }
}

function deleteVacuna($id_vacuna) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Vacunas WHERE id_vacuna = :id_vacuna";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_vacuna' => $id_vacuna]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar vacuna: " . $e->getMessage());
    }
}

?>