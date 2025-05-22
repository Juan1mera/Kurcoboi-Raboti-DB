<?php
require_once __DIR__ . './../db_connect.php';

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
    return getFilteredAnimals(); // Default to no filters
}

function getFilteredAnimals(
    $id = null,
    $codigo = null,
    $id_raza = null,
    $id_corral = null,
    $sexo = null,
    $peso_min = null,
    $peso_max = null,
    $estado_salud = null,
    $fecha_nacimiento_start = null,
    $fecha_nacimiento_end = null,
    $fecha_ingreso_start = null,
    $fecha_ingreso_end = null
) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT a.*, r.nombre AS nombre_raza, c.nombre AS nombre_corral 
                FROM Animales a
                INNER JOIN Razas r ON a.id_raza = r.id_raza
                INNER JOIN Corrales c ON a.id_corral = c.id_corral
                WHERE a.activo = TRUE";
        $params = [];

        // Apply filters
        if ($id !== null && $id !== '') {
            $sql .= " AND a.id_animal = :id";
            $params[':id'] = $id;
        }
        if ($codigo !== null && $codigo !== '') {
            $sql .= " AND a.codigo LIKE :codigo";
            $params[':codigo'] = '%' . $codigo . '%';
        }
        if ($id_raza !== null && $id_raza !== '') {
            $sql .= " AND a.id_raza = :id_raza";
            $params[':id_raza'] = $id_raza;
        }
        if ($id_corral !== null && $id_corral !== '') {
            $sql .= " AND a.id_corral = :id_corral";
            $params[':id_corral'] = $id_corral;
        }
        if ($sexo !== null && $sexo !== '') {
            $sql .= " AND a.sexo = :sexo";
            $params[':sexo'] = $sexo;
        }
        if ($peso_min !== null && $peso_min !== '') {
            $sql .= " AND a.peso >= :peso_min";
            $params[':peso_min'] = $peso_min;
        }
        if ($peso_max !== null && $peso_max !== '') {
            $sql .= " AND a.peso <= :peso_max";
            $params[':peso_max'] = $peso_max;
        }
        if ($estado_salud !== null && $estado_salud !== '') {
            $sql .= " AND a.estado_salud = :estado_salud";
            $params[':estado_salud'] = $estado_salud;
        }
        if ($fecha_nacimiento_start !== null && $fecha_nacimiento_start !== '') {
            $sql .= " AND a.fecha_nacimiento >= :fecha_nacimiento_start";
            $params[':fecha_nacimiento_start'] = $fecha_nacimiento_start;
        }
        if ($fecha_nacimiento_end !== null && $fecha_nacimiento_end !== '') {
            $sql .= " AND a.fecha_nacimiento <= :fecha_nacimiento_end";
            $params[':fecha_nacimiento_end'] = $fecha_nacimiento_end;
        }
        if ($fecha_ingreso_start !== null && $fecha_ingreso_start !== '') {
            $sql .= " AND a.fecha_ingreso >= :fecha_ingreso_start";
            $params[':fecha_ingreso_start'] = $fecha_ingreso_start;
        }
        if ($fecha_ingreso_end !== null && $fecha_ingreso_end !== '') {
            $sql .= " AND a.fecha_ingreso <= :fecha_ingreso_end";
            $params[':fecha_ingreso_end'] = $fecha_ingreso_end;
        }

        $sql .= " ORDER BY a.id_animal";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener animales filtrados: " . $e->getMessage());
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