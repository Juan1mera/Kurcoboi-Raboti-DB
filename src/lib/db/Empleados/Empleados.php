<?php

require_once __DIR__ . '/db_connect.php';

function createEmpleado($nombre, $rol, $fecha_contratacion, $salario, $telefono, $activo) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Empleados (nombre, rol, fecha_contratacion, salario, telefono, activo) 
                VALUES (:nombre, :rol, :fecha_contratacion, :salario, :telefono, :activo)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':rol' => $rol,
            ':fecha_contratacion' => $fecha_contratacion,
            ':salario' => $salario,
            ':telefono' => $telefono === '' ? null : $telefono,
            ':activo' => $activo
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear empleado: " . $e->getMessage());
    }
}

function getEmpleados() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Empleados WHERE activo = TRUE";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener empleados: " . $e->getMessage());
    }
}

function getEmpleadoById($id_empleado) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Empleados WHERE id_empleado = :id_empleado AND activo = TRUE";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_empleado' => $id_empleado]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener empleado: " . $e->getMessage());
    }
}

function updateEmpleado($id_empleado, $nombre, $rol, $fecha_contratacion, $salario, $telefono, $activo) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Empleados SET 
                nombre = :nombre, 
                rol = :rol, 
                fecha_contratacion = :fecha_contratacion, 
                salario = :salario, 
                telefono = :telefono, 
                activo = :activo 
                WHERE id_empleado = :id_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_empleado' => $id_empleado,
            ':nombre' => $nombre,
            ':rol' => $rol,
            ':fecha_contratacion' => $fecha_contratacion,
            ':salario' => $salario,
            ':telefono' => $telefono === '' ? null : $telefono,
            ':activo' => $activo
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar empleado: " . $e->getMessage());
    }
}

function deleteEmpleado($id_empleado) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Empleados SET activo = FALSE WHERE id_empleado = :id_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_empleado' => $id_empleado]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar empleado: " . $e->getMessage());
    }
}

?>