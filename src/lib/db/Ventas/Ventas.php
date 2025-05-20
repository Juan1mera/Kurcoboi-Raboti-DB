<?php

require_once __DIR__ . '/db_connect.php';

function createVenta($id_cliente, $fecha, $estado, $monto_total) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Ventas (id_cliente, fecha, estado, monto_total) 
                VALUES (:id_cliente, :fecha, :estado, :monto_total)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_cliente' => $id_cliente,
            ':fecha' => $fecha,
            ':estado' => $estado,
            ':monto_total' => $monto_total
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear venta: " . $e->getMessage());
    }
}

function getVentas() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT v.*, c.nombre AS nombre_cliente
                FROM Ventas v
                INNER JOIN Clientes c ON v.id_cliente = c.id_cliente";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener ventas: " . $e->getMessage());
    }
}

function getVentaById($id_venta) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT v.*, c.nombre AS nombre_cliente
                FROM Ventas v
                INNER JOIN Clientes c ON v.id_cliente = c.id_cliente
                WHERE v.id_venta = :id_venta";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_venta' => $id_venta]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener venta: " . $e->getMessage());
    }
}

function updateVenta($id_venta, $id_cliente, $fecha, $estado, $monto_total) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Ventas SET 
                id_cliente = :id_cliente, 
                fecha = :fecha, 
                estado = :estado, 
                monto_total = :monto_total 
                WHERE id_venta = :id_venta";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_venta' => $id_venta,
            ':id_cliente' => $id_cliente,
            ':fecha' => $fecha,
            ':estado' => $estado,
            ':monto_total' => $monto_total
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar venta: " . $e->getMessage());
    }
}

function deleteVenta($id_venta) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Ventas WHERE id_venta = :id_venta";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_venta' => $id_venta]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar venta: " . $e->getMessage());
    }
}

?>