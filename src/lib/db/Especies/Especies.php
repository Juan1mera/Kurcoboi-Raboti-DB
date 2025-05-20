<?php

require_once __DIR__ . '/db_connect.php';

function createEspecie($nombre, $descripcion) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Especies (nombre, descripcion) 
                VALUES (:nombre, :descripcion)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion === '' ? null : $descripcion
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear especie: " . $e->getMessage());
    }
}

function getEspecies() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Especies";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener especies: " . $e->getMessage());
    }
}

function getEspecieById($id_especie) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Especies WHERE id_especie = :id_especie";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_especie' => $id_especie]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener especie: " . $e->getMessage());
    }
}

function updateEspecie($id_especie, $nombre, $descripcion) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Especies SET 
                nombre = :nombre, 
                descripcion = :descripcion 
                WHERE id_especie = :id_especie";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_especie' => $id_especie,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion === '' ? null : $descripcion
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar especie: " . $e->getMessage());
    }
}

function deleteEspecie($id_especie) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Especies WHERE id_especie = :id_especie";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_especie' => $id_especie]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar especie: " . $e->getMessage());
    }
}

?>