<?php

require_once __DIR__ . '/db_connect.php';

function createTransaccion($tipo, $monto, $fecha, $descripcion, $id_venta, $id_producto) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Finanzas (tipo, monto, fecha, descripcion, id_venta, id_producto) 
                VALUES (:tipo, :monto, :fecha, :descripcion, :id_venta, :id_producto)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':tipo' => $tipo,
            ':monto' => $monto,
            ':fecha' => $fecha,
            ':descripcion' => $descripcion,
            ':id_venta' => $id_venta === '' ? null : $id_venta,
            ':id_producto' => $id_producto === '' ? null : $id_producto
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear transacción: " . $e->getMessage());
    }
}

function getTransacciones() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Finanzas";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener transacciones: " . $e->getMessage());
    }
}

function getTransaccionById($id_transaccion) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Finanzas WHERE id_transaccion = :id_transaccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_transaccion' => $id_transaccion]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener transacción: " . $e->getMessage());
    }
}

function updateTransaccion($id_transaccion, $tipo, $monto, $fecha, $descripcion, $id_venta, $id_producto) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Finanzas SET 
                tipo = :tipo, 
                monto = :monto, 
                fecha = :fecha, 
                descripcion = :descripcion, 
                id_venta = :id_venta, 
                id_producto = :id_producto 
                WHERE id_transaccion = :id_transaccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_transaccion' => $id_transaccion,
            ':tipo' => $tipo,
            ':monto' => $monto,
            ':fecha' => $fecha,
            ':descripcion' => $descripcion,
            ':id_venta' => $id_venta === '' ? null : $id_venta,
            ':id_producto' => $id_producto === '' ? null : $id_producto
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar transacción: " . $e->getMessage());
    }
}

function deleteTransaccion($id_transaccion) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Finanzas WHERE id_transaccion = :id_transaccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_transaccion' => $id_transaccion]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar transacción: " . $e->getMessage());
    }
}

?>