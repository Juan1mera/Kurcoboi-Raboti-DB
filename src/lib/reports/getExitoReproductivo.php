<?php
require_once __DIR__ . '/../db/db_connect.php';


function getExitoReproductivo() {
    try {
        $conn = getDBConnection();
        $sql = "SELECT e.nombre AS nombre_especie, ra.nombre AS nombre_raza, 
                       COUNT(*) AS total_gestaciones,
                       SUM(CASE WHEN r.estado = 'Finalizado' AND r.numero_crias > 0 THEN 1 ELSE 0 END) AS partos_exitosos,
                       ROUND(AVG(r.numero_crias), 2) AS promedio_crias,
                       r.estado AS estado_gestacion
                FROM Reproduccion r
                INNER JOIN Animales a ON r.id_animal_hembra = a.id_animal
                INNER JOIN Razas ra ON a.id_raza = ra.id_raza
                INNER JOIN Especies e ON ra.id_especie = e.id_especie
                GROUP BY e.id_especie, ra.id_raza, r.estado
                ORDER BY total_gestaciones DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener reporte de reproducción: " . $e->getMessage());
    }
}
?>