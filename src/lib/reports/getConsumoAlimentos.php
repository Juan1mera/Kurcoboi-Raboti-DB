<?php
require_once __DIR__ . '/db_connect.php';

function getConsumoAlimentos($fechaInicio = null, $fechaFin = null) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT p.nombre AS nombre_producto, c.nombre AS nombre_corral, a.codigo AS codigo_animal, 
                       COALESCE(ea.nombre, ec.nombre) AS nombre_especie, SUM(ca.cantidad) AS total_cantidad, 
                       MAX(ca.fecha) AS ultima_fecha
                FROM Consumo_Alimentos ca
                INNER JOIN Productos_Inventario p ON ca.id_producto = p.id_producto
                LEFT JOIN Animales a ON ca.id_animal = a.id_animal
                LEFT JOIN Razas r ON a.id_raza = r.id_raza
                LEFT JOIN Especies ea ON r.id_especie = ea.id_especie
                LEFT JOIN Corrales c ON ca.id_corral = c.id_corral
                LEFT JOIN Especies ec ON c.id_especie = ec.id_especie
                WHERE ca.fecha BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY p.id_producto, c.id_corral, a.id_animal, COALESCE(ea.nombre, ec.nombre)
                ORDER BY total_cantidad DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fecha_inicio' => $fechaInicio ?? '2000-01-01',
            ':fecha_fin' => $fechaFin ?? date('Y-m-d')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error al obtener reporte de consumo de alimentos: " . $e->getMessage());
    }
}
?>