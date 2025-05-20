<?php

require_once __DIR__ . '/db_connect.php';

function createReproduccion($id_animal_hembra, $id_animal_macho, $fecha_inicio_gestacion, $fecha_parto_estimada, $fecha_parto_real, $numero_crias, $estado, $notas) {
    try {
        $conn = getDBConnection();
        $sql = "INSERT INTO Reproduccion (id_animal_hembra, id_animal_macho, fecha_inicio_gestacion, fecha_parto_estimada, fecha_parto_real, numero_crias, estado, notas) 
                VALUES (:id_animal_hembra, :id_animal_macho, :fecha_inicio_gestacion, :fecha_parto_estimada, :fecha_parto_real, :numero_crias, :estado, :notas)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_animal_hembra' => $id_animal_hembra,
            ':id_animal_macho' => $id_animal_macho === '' ? null : $id_animal_macho,
            ':fecha_inicio_gestacion' => $fecha_inicio_gestacion,
            ':fecha_parto_estimada' => $fecha_parto_estimada === '' ? null : $fecha_parto_estimada,
            ':fecha_parto_real' => $fecha_parto_real === '' ? null : $fecha_parto_real,
            ':numero_crias' => $numero_crias === '' ? null : $numero_crias,
            ':estado' => $estado,
            ':notas' => $notas === '' ? null : $notas
        ]);
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Error al crear reproducción: " . $e->getMessage());
    }
}

function getReproducciones() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT r.*, ah.*, am.*, eh.nombre AS nombre_especie_hembra, em.nombre AS nombre_especie_macho
                FROM Reproduccion r
                INNER JOIN Animales ah ON r.id_animal_hembra = ah.id_animal
                LEFT JOIN Animales am ON r.id_animal_macho = am.id_animal
                INNER JOIN Especies eh ON ah.id_especie = eh.id_especie
                LEFT JOIN Especies em ON am.id_especie = em.id_especie";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener reproducciones: " . $e->getMessage());
    }
}

function getReproduccionById($id_reproduccion) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT r.*, ah.*, am.*, eh.nombre AS nombre_especie_hembra, em.nombre AS nombre_especie_macho
                FROM Reproduccion r
                INNER JOIN Animales ah ON r.id_animal_hembra = ah.id_animal
                LEFT JOIN Animales am ON r.id_animal_macho = am.id_animal
                INNER JOIN Especies eh ON ah.id_especie = eh.id_especie
                LEFT JOIN Especies em ON am.id_especie = em.id_especie
                WHERE r.id_reproduccion = :id_reproduccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_reproduccion' => $id_reproduccion]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener reproducción: " . $e->getMessage());
    }
}

function updateReproduccion($id_reproduccion, $id_animal_hembra, $id_animal_macho, $fecha_inicio_gestacion, $fecha_parto_estimada, $fecha_parto_real, $numero_crias, $estado, $notas) {
    try {
        $conn = getDBConnection();
        $sql = "UPDATE Reproduccion SET 
                id_animal_hembra = :id_animal_hembra, 
                id_animal_macho = :id_animal_macho, 
                fecha_inicio_gestacion = :fecha_inicio_gestacion, 
                fecha_parto_estimada = :fecha_parto_estimada, 
                fecha_parto_real = :fecha_parto_real, 
                numero_crias = :numero_crias, 
                estado = :estado, 
                notas = :notas 
                WHERE id_reproduccion = :id_reproduccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_reproduccion' => $id_reproduccion,
            ':id_animal_hembra' => $id_animal_hembra,
            ':id_animal_macho' => $id_animal_macho === '' ? null : $id_animal_macho,
            ':fecha_inicio_gestacion' => $fecha_inicio_gestacion,
            ':fecha_parto_estimada' => $fecha_parto_estimada === '' ? null : $fecha_parto_estimada,
            ':fecha_parto_real' => $fecha_parto_real === '' ? null : $fecha_parto_real,
            ':numero_crias' => $numero_crias === '' ? null : $numero_crias,
            ':estado' => $estado,
            ':notas' => $notas === '' ? null : $notas
        ]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al actualizar reproducción: " . $e->getMessage());
    }
}

function deleteReproduccion($id_reproduccion) {
    try {
        $conn = getDBConnection();
        $sql = "DELETE FROM Reproduccion WHERE id_reproduccion = :id_reproduccion";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_reproduccion' => $id_reproduccion]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        throw new Exception("Error al eliminar reproducción: " . $e->getMessage());
    }
}

?>