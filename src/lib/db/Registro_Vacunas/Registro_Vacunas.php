<?php

require_once __DIR__ . '/db_connect.php';

function createRegistroVacuna($id_animal, $id_vacuna, $fecha_aplicacion, $dosis, $notas) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Registro_Vacunas (id_animal, id_vacuna, fecha_aplicacion, dosis, notas) 
                VALUES (:id_animal, :id_vacuna, :fecha_aplicacion, :dosis, :notas)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_animal' => $id_animal,
            ':id_vacuna' => $id_vacuna,
            ':fecha_aplicacion' => $fecha_aplicacion,
            ':dosis' => $dosis,
            ':notas' => $notas === '' ? null : $notas
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear registro de vacunación: " . $e->getMessage());
    }
}

function getRegistrosVacunas() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT rv.*, a.*, v.nombre AS nombre_vacuna, e.nombre AS nombre_especie
                FROM Registro_Vacunas rv
                INNER JOIN Animales a ON rv.id_animal = a.id_animal
                INNER JOIN Vacunas v ON rv.id_vacuna = v.id_vacuna
                INNER JOIN Especies e ON a.id_especie = e.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener registros de vacunación: " . $e->getMessage());
    }
}

function getRegistroVacunaById($id_registro_vacuna) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT rv.*, a.*, v.nombre AS nombre_vacuna, e.nombre AS nombre_especie
                FROM Registro_Vacunas rv
                INNER JOIN Animales a ON rv.id_animal = a.id_animal
                INNER JOIN Vacunas v ON rv.id_vacuna = v.id_vacuna
                INNER JOIN Especies e ON a.id_especie = e.id_especie
                WHERE rv.id_registro_vacuna = :id_registro_vacuna";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_registro_vacuna' => $id_registro_vacuna]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener registro de vacunación: " . $e->getMessage());
    }
}

function updateRegistroVacuna($id_registro_vacuna, $id_animal, $id_vacuna, $fecha_aplicacion, $dosis, $notas) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Registro_Vacunas SET 
                id_animal = :id_animal, 
                id_vacuna = :id_vacuna, 
                fecha_aplicacion = :fecha_aplicacion, 
                dosis = :dosis, 
                notas = :notas 
                WHERE id_registro_vacuna = :id_registro_vacuna";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_registro_vacuna' => $id_registro_vacuna,
            ':id_animal' => $id_animal,
            ':id_vacuna' => $id_vacuna,
            ':fecha_aplicacion' => $fecha_aplicacion,
            ':dosis' => $dosis,
            ':notas' => $notas === '' ? null : $notas
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar registro de vacunación: " . $e->getMessage());
    }
}

function deleteRegistroVacuna($id_registro_vacuna) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Registro_Vacunas WHERE id_registro_vacuna = :id_registro_vacuna";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_registro_vacuna' => $id_registro_vacuna]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar registro de vacunación: " . $e->getMessage());
    }
}

?>