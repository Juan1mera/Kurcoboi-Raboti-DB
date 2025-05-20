<?php

require_once __DIR__ . '../../db_connect.php';

function createAnimal($id_raza, $id_corral, $codigo, $fecha_nacimiento, $sexo, $peso, $estado_salud, $fecha_ingreso) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Animales (id_raza, id_corral, codigo, fecha_nacimiento, sexo, peso, estado_salud, fecha_ingreso, activo) 
                VALUES (:id_raza, :id_corral, :codigo, :fecha_nacimiento, :sexo, :peso, :estado_salud, :fecha_ingreso, TRUE)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_raza' => $id_raza,
            ':id_corral' => $id_corral,
            ':codigo' => $codigo,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':sexo' => $sexo,
            ':peso' => $peso,
            ':estado_salud' => $estado_salud,
            ':fecha_ingreso' => $fecha_ingreso
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear animal: " . $e->getMessage());
    }
}

function getAnimals() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT a.*, r.nombre AS nombre_raza, c.nombre AS nombre_corral 
                FROM Animales a
                INNER JOIN Razas r ON a.id_raza = r.id_raza
                INNER JOIN Corrales c ON a.id_corral = c.id_corral
                WHERE a.activo = TRUE";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener animales: " . $e->getMessage());
    }
}

function getAnimalById($id_animal) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT a.*, r.nombre AS nombre_raza, c.nombre AS nombre_corral 
                FROM Animales a
                INNER JOIN Razas r ON a.id_raza = r.id_raza
                INNER JOIN Corrales c ON a.id_corral = c.id_corral
                WHERE a.id_animal = :id_animal AND a.activo = TRUE";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_animal' => $id_animal]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener animal: " . $e->getMessage());
    }
}

function updateAnimal($id_animal, $id_raza, $id_corral, $codigo, $fecha_nacimiento, $sexo, $peso, $estado_salud, $fecha_ingreso) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Animales SET 
                id_raza = :id_raza, 
                id_corral = :id_corral, 
                codigo = :codigo, 
                fecha_nacimiento = :fecha_nacimiento, 
                sexo = :sexo, 
                peso = :peso, 
                estado_salud = :estado_salud, 
                fecha_ingreso = :fecha_ingreso 
                WHERE id_animal = :id_animal AND activo = TRUE";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_animal' => $id_animal,
            ':id_raza' => $id_raza,
            ':id_corral' => $id_corral,
            ':codigo' => $codigo,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':sexo' => $sexo,
            ':peso' => $peso,
            ':estado_salud' => $estado_salud,
            ':fecha_ingreso' => $fecha_ingreso
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar animal: " . $e->getMessage());
    }
}

function deleteAnimal($id_animal) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Animales SET activo = FALSE WHERE id_animal = :id_animal";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_animal' => $id_animal]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar animal: " . $e->getMessage());
    }
}

?>