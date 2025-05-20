<?php

require_once __DIR__ . '/db_connect.php';

function createConsumoAlimento($id_producto, $id_animal, $id_corral, $cantidad, $fecha) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Consumo_Alimentos (id_producto, id_animal, id_corral, cantidad, fecha) 
                VALUES (:id_producto, :id_animal, :id_corral, :cantidad, :fecha)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_producto' => $id_producto,
            ':id_animal' => $id_animal === '' ? null : $id_animal,
            ':id_corral' => $id_corral === '' ? null : $id_corral,
            ':cantidad' => $cantidad,
            ':fecha' => $fecha
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear consumo de alimento: " . $e->getMessage());
    }
}

function getConsumosAlimentos() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT ca.*, p.nombre AS nombre_producto, a.*, c.*, e.nombre AS nombre_especie
                FROM Consumo_Alimentos ca
                INNER JOIN Productos_Inventario p ON ca.id_producto = p.id_producto
                LEFT JOIN Animales a ON ca.id_animal = a.id_animal
                LEFT JOIN Corrales c ON ca.id_corral = c.id_corral
                LEFT JOIN Especies e ON a.id_especie = e.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener consumos de alimentos: " . $e->getMessage());
    }
}

function getConsumoAlimentoById($id_consumo) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT ca.*, p.nombre AS nombre_producto, a.*, c.*, e.nombre AS nombre_especie
                FROM Consumo_Alimentos ca
                INNER JOIN Productos_Inventario p ON ca.id_producto = p.id_producto
                LEFT JOIN Animales a ON ca.id_animal = a.id_animal
                LEFT JOIN Corrales c ON ca.id_corral = c.id_corral
                LEFT JOIN Especies e ON a.id_especie = e.id_especie
                WHERE ca.id_consumo = :id_consumo";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_consumo' => $id_consumo]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener consumo de alimento: " . $e->getMessage());
    }
}

function updateConsumoAlimento($id_consumo, $id_producto, $id_animal, $id_corral, $cantidad, $fecha) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Consumo_Alimentos SET 
                id_producto = :id_producto, 
                id_animal = :id_animal, 
                id_corral = :id_corral, 
                cantidad = :cantidad, 
                fecha = :fecha 
                WHERE id_consumo = :id_consumo";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_consumo' => $id_consumo,
            ':id_producto' => $id_producto,
            ':id_animal' => $id_animal === '' ? null : $id_animal,
            ':id_corral' => $id_corral === '' ? null : $id_corral,
            ':cantidad' => $cantidad,
            ':fecha' => $fecha
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar consumo de alimento: " . $e->getMessage());
    }
}

function deleteConsumoAlimento($id_consumo) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Consumo_Alimentos WHERE id_consumo = :id_consumo";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_consumo' => $id_consumo]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar consumo de alimento: " . $e->getMessage());
    }
}

?>