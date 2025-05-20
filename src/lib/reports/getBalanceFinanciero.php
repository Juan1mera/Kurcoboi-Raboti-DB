<?php

require_once __DIR__ . '/db_connect.php';

function getBalanceFinanciero($fechaInicio = null, $fechaFin = null) {
    try {
        $conn = getDBConnection();
        $sql = "SELECT f.tipo, SUM(f.monto) AS total_monto, c.nombre AS nombre_cliente, 
                       MONTH(f.fecha) AS mes, YEAR(f.fecha) AS anio
                FROM Finanzas f
                LEFT JOIN Ventas v ON f.id_venta = v.id_venta
                LEFT JOIN Clientes c ON v.id_cliente = c.id_cliente
                WHERE f.fecha BETWEEN :fecha_inicio AND :fecha_fin
                GROUP BY f.tipo, MONTH(f.fecha), YEAR(f.fecha), c.id_cliente
                ORDER BY anio, mes, f.tipo";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':fecha_inicio' => $fechaInicio ?? '2000-01-01',
            ':fecha_fin' => $fechaFin ?? date('Y-m-d')
        ]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Error al obtener reporte financiero: " . $e->getMessage());
    }
}
