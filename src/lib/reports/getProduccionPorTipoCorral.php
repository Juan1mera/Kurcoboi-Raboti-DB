<?php
require_once __DIR__ . '/../db/db_connect.php';


function getProduccionPorTipoCorral($fechaInicio = null, $fechaFin = null) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT p.tipo_producto, c.nombre AS nombre_corral, e.nombre AS nombre_especie, 
                       SUM(p.cantidad) AS total_cantidad, p.unidad_medida
                FROM Produccion p
                LEFT JOIN Corrales c ON p.id_corral = c.id_corral
                LEFT JOIN Especies e ON c.id_especie = e.id_especie
                WHERE p.fecha BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY p.tipo_producto, c.id_corral, e.id_especie, p.unidad_medida
                ORDER BY total_cantidad DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fecha_inicio' => $fechaInicio ?? '2000-01-01',
            ':fecha_fin' => $fechaFin ?? date('Y-m-d')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener reporte de producción: " . $e->getMessage());
    }
}
?>