<?php

require_once __DIR__ . './../db_connect.php';

function createRaza($id_especie, $nombre, $descripcion) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Razas (id_especie, nombre, descripcion) 
                VALUES (:id_especie, :nombre, :descripcion)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_especie' => $id_especie,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion === '' ? null : $descripcion
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear raza: " . $e->getMessage());
    }
}

function getRazas() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Razas";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener razas: " . $e->getMessage());
    }
}

function getRazaById($id_raza) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Razas WHERE id_raza = :id_raza";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_raza' => $id_raza]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener raza: " . $e->getMessage());
    }
}

function updateRaza($id_raza, $id_especie, $nombre, $descripcion) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Razas SET 
                id_especie = :id_especie, 
                nombre = :nombre, 
                descripcion = :descripcion 
                WHERE id_raza = :id_raza";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_raza' => $id_raza,
            ':id_especie' => $id_especie,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion === '' ? null : $descripcion
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar raza: " . $e->getMessage());
    }
}

function deleteRaza($id_raza) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Razas WHERE id_raza = :id_raza";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_raza' => $id_raza]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar raza: " . $e->getMessage());
    }
}

?>