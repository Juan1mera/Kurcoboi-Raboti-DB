<?php

require_once __DIR__ . '/db_connect.php';

function createProductoInventario($nombre, $tipo, $cantidad, $unidad_medida, $precio_unitario, $stock_minimo, $id_especie) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Productos_Inventario (nombre, tipo, cantidad, unidad_medida, precio_unitario, stock_minimo, id_especie) 
                VALUES (:nombre, :tipo, :cantidad, :unidad_medida, :precio_unitario, :stock_minimo, :id_especie)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':tipo' => $tipo,
            ':cantidad' => $cantidad,
            ':unidad_medida' => $unidad_medida,
            ':precio_unitario' => $precio_unitario === '' ? null : $precio_unitario,
            ':stock_minimo' => $stock_minimo === '' ? null : $stock_minimo,
            ':id_especie' => $id_especie === '' ? null : $id_especie
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear producto de inventario: " . $e->getMessage());
    }
}

function getProductosInventario() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Productos_Inventario";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener productos de inventario: " . $e->getMessage());
    }
}

function getProductoInventarioById($id_producto) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Productos_Inventario WHERE id_producto = :id_producto";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_producto' => $id_producto]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener producto de inventario: " . $e->getMessage());
    }
}

function updateProductoInventario($id_producto, $nombre, $tipo, $cantidad, $unidad_medida, $precio_unitario, $stock_minimo, $id_especie) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Productos_Inventario SET 
                nombre = :nombre, 
                tipo = :tipo, 
                cantidad = :cantidad, 
                unidad_medida = :unidad_medida, 
                precio_unitario = :precio_unitario, 
                stock_minimo = :stock_minimo, 
                id_especie = :id_especie 
                WHERE id_producto = :id_producto";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_producto' => $id_producto,
            ':nombre' => $nombre,
            ':tipo' => $tipo,
            ':cantidad' => $cantidad,
            ':unidad_medida' => $unidad_medida,
            ':precio_unitario' => $precio_unitario === '' ? null : $precio_unitario,
            ':stock_minimo' => $stock_minimo === '' ? null : $stock_minimo,
            ':id_especie' => $id_especie === '' ? null : $id_especie
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar producto de inventario: " . $e->getMessage());
    }
}

function deleteProductoInventario($id_producto) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Productos_Inventario WHERE id_producto = :id_producto";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_producto' => $id_producto]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar producto de inventario: " . $e->getMessage());
    }
}

?>