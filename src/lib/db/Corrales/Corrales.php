<?php

require_once __DIR__ . './../db_connect.php';

function createCorral($nombre, $id_especie, $capacidad, $ubicacion, $estado) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Corrales (nombre, id_especie, capacidad, ubicacion, estado) 
                VALUES (:nombre, :id_especie, :capacidad, :ubicacion, :estado)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':id_especie' => $id_especie,
            ':capacidad' => $capacidad,
            ':ubicacion' => $ubicacion === '' ? null : $ubicacion,
            ':estado' => $estado
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear corral: " . $e->getMessage());
    }
}

function getCorrales() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT c.*, e.nombre AS nombre_especie 
                FROM Corrales c
                INNER JOIN Especies e ON c.id_especie = e.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener corrales: " . $e->getMessage());
    }
}

function getCorralById($id_corral) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT c.*, e.nombre AS nombre_especie 
                FROM Corrales c
                INNER JOIN Especies e ON c.id_especie = e.id_especie
                WHERE c.id_corral = :id_corral";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_corral' => $id_corral]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener corral: " . $e->getMessage());
    }
}

function updateCorral($id_corral, $nombre, $id_especie, $capacidad, $ubicacion, $estado) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Corrales SET 
                nombre = :nombre, 
                id_especie = :id_especie, 
                capacidad = :capacidad, 
                ubicacion = :ubicacion, 
                estado = :estado 
                WHERE id_corral = :id_corral";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_corral' => $id_corral,
            ':nombre' => $nombre,
            ':id_especie' => $id_especie,
            ':capacidad' => $capacidad,
            ':ubicacion' => $ubicacion === '' ? null : $ubicacion,
            ':estado' => $estado
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar corral: " . $e->getMessage());
    }
}

function deleteCorral($id_corral) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Corrales WHERE id_corral = :id_corral";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_corral' => $id_corral]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar corral: " . $e->getMessage());
    }
}

?>