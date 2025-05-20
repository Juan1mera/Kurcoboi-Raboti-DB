<?php

require_once __DIR__ . '/db_connect.php';

function createCliente($nombre, $telefono, $direccion, $email) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Clientes (nombre, telefono, direccion, email) 
                VALUES (:nombre, :telefono, :direccion, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':telefono' => $telefono === '' ? null : $telefono,
            ':direccion' => $direccion === '' ? null : $direccion,
            ':email' => $email === '' ? null : $email
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear cliente: " . $e->getMessage());
    }
}

function getClientes() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Clientes";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener clientes: " . $e->getMessage());
    }
}

function getClienteById($id_cliente) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT * FROM Clientes WHERE id_cliente = :id_cliente";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_cliente' => $id_cliente]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener cliente: " . $e->getMessage());
    }
}

function updateCliente($id_cliente, $nombre, $telefono, $direccion, $email) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Clientes SET 
                nombre = :nombre, 
                telefono = :telefono, 
                direccion = :direccion, 
                email = :email 
                WHERE id_cliente = :id_cliente";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_cliente' => $id_cliente,
            ':nombre' => $nombre,
            ':telefono' => $telefono === '' ? null : $telefono,
            ':direccion' => $direccion === '' ? null : $direccion,
            ':email' => $email === '' ? null : $email
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar cliente: " . $e->getMessage());
    }
}

function deleteCliente($id_cliente) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Clientes WHERE id_cliente = :id_cliente";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_cliente' => $id_cliente]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar cliente: " . $e->getMessage());
    }
}

?>