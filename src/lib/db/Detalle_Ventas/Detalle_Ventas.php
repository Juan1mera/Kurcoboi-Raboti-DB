<?php

require_once __DIR__ . '/db_connect.php';

function createDetalleVenta($id_venta, $id_producto, $tipo_producto, $cantidad, $precio_unitario) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Detalle_Ventas (id_venta, id_producto, tipo_producto, cantidad, precio_unitario) 
                VALUES (:id_venta, :id_producto, :tipo_producto, :cantidad, :precio_unitario)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_venta' => $id_venta,
            ':id_producto' => $id_producto === '' ? null : $id_producto,
            ':tipo_producto' => $tipo_producto,
            ':cantidad' => $cantidad,
            ':precio_unitario' => $precio_unitario
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear detalle de venta: " . $e->getMessage());
    }
}

function getDetallesVentas() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT dv.*, v.fecha AS fecha_venta, c.nombre AS nombre_cliente, p.nombre AS nombre_producto
                FROM Detalle_Ventas dv
                INNER JOIN Ventas v ON dv.id_venta = v.id_venta
                INNER JOIN Clientes c ON v.id_cliente = c.id_cliente
                LEFT JOIN Productos_Inventario p ON dv.id_producto = p.id_producto";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener detalles de ventas: " . $e->getMessage());
    }
}

function getDetalleVentaById($id_detalle) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT dv.*, v.fecha AS fecha_venta, c.nombre AS nombre_cliente, p.nombre AS nombre_producto
                FROM Detalle_Ventas dv
                INNER JOIN Ventas v ON dv.id_venta = v.id_venta
                INNER JOIN Clientes c ON v.id_cliente = c.id_cliente
                LEFT JOIN Productos_Inventario p ON dv.id_producto = p.id_producto
                WHERE dv.id_detalle = :id_detalle";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_detalle' => $id_detalle]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener detalle de venta: " . $e->getMessage());
    }
}

function updateDetalleVenta($id_detalle, $id_venta, $id_producto, $tipo_producto, $cantidad, $precio_unitario) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Detalle_Ventas SET 
                id_venta = :id_venta, 
                id_producto = :id_producto, 
                tipo_producto = :tipo_producto, 
                cantidad = :cantidad, 
                precio_unitario = :precio_unitario 
                WHERE id_detalle = :id_detalle";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_detalle' => $id_detalle,
            ':id_venta' => $id_venta,
            ':id_producto' => $id_producto === '' ? null : $id_producto,
            ':tipo_producto' => $tipo_producto,
            ':cantidad' => $cantidad,
            ':precio_unitario' => $precio_unitario
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar detalle de venta: " . $e->getMessage());
    }
}

function deleteDetalleVenta($id_detalle) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Detalle_Ventas WHERE id_detalle = :id_detalle";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_detalle' => $id_detalle]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar detalle de venta: " . $e->getMessage());
    }
}

?>