<?php

require_once __DIR__ . '/db_connect.php';

function createProduccion($id_animal, $id_corral, $tipo_producto, $cantidad, $unidad_medida, $fecha) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Produccion (id_animal, id_corral, tipo_producto, cantidad, unidad_medida, fecha) 
                VALUES (:id_animal, :id_corral, :tipo_producto, :cantidad, :unidad_medida, :fecha)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_animal' => $id_animal === '' ? null : $id_animal,
            ':id_corral' => $id_corral === '' ? null : $id_corral,
            ':tipo_producto' => $tipo_producto,
            ':cantidad' => $cantidad,
            ':unidad_medida' => $unidad_medida,
            ':fecha' => $fecha
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear producción: " . $e->getMessage());
    }
}

function getProducciones() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT p.*, a.*, c.*, e.nombre AS nombre_especie
                FROM Produccion p
                LEFT JOIN Animales a ON p.id_animal = a.id_animal
                LEFT JOIN Corrales c ON p.id_corral = c.id_corral
                LEFT JOIN Especies e ON a.id_especie = e.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener producciones: " . $e->getMessage());
    }
}

function getProduccionById($id_produccion) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT p.*, a.*, c.*, e.nombre AS nombre_especie
                FROM Produccion p
                LEFT JOIN Animales a ON p.id_animal = a.id_animal
                LEFT JOIN Corrales c ON p.id_corral = c.id_corral
                LEFT JOIN Especies e ON a.id_especie = e.id_especie
                WHERE p.id_produccion = :id_produccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_produccion' => $id_produccion]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener producción: " . $e->getMessage());
    }
}

function updateProduccion($id_produccion, $id_animal, $id_corral, $tipo_producto, $cantidad, $unidad_medida, $fecha) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Produccion SET 
                id_animal = :id_animal, 
                id_corral = :id_corral, 
                tipo_producto = :tipo_producto, 
                cantidad = :cantidad, 
                unidad_medida = :unidad_medida, 
                fecha = :fecha 
                WHERE id_produccion = :id_produccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_produccion' => $id_produccion,
            ':id_animal' => $id_animal === '' ? null : $id_animal,
            ':id_corral' => $id_corral === '' ? null : $id_corral,
            ':tipo_producto' => $tipo_producto,
            ':cantidad' => $cantidad,
            ':unidad_medida' => $unidad_medida,
            ':fecha' => $fecha
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar producción: " . $e->getMessage());
    }
}

function deleteProduccion($id_produccion) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Produccion WHERE id_produccion = :id_produccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_produccion' => $id_produccion]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar producción: " . $e->getMessage());
    }
}

?>