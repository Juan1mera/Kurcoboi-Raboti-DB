<?php
require_once __DIR__ . '/../db/db_connect.php';


function getEstadoSaludAnimales() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT e.nombre AS nombre_especie, r.nombre AS nombre_raza, a.estado_salud, 
                       COUNT(*) AS cantidad_animales,
                       ROUND((COUNT(*) * 100 / SUM(COUNT(*)) OVER (PARTITION BY e.id_especie, r.id_raza)), 2) AS porcentaje
                FROM Animales a
                INNER JOIN Razas r ON a.id_raza = r.id_raza
                INNER JOIN Especies e ON r.id_especie = e.id_especie
                WHERE a.activo = TRUE
                GROUP BY e.id_especie, r.id_raza, a.estado_salud
                ORDER BY e.nombre, r.nombre, a.estado_salud";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener reporte de estado de salud: " . $e->getMessage());
    }
}
?>