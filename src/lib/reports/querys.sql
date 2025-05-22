-- getBalanceFinanciero
SELECT f.tipo, ROUND(SUM(f.monto), 2) AS total_monto, c.nombre AS nombre_cliente, 
       CONCAT(MONTH(f.fecha), '-', YEAR(f.fecha)) AS periodo
FROM Finanzas f
LEFT JOIN Ventas v ON f.id_venta = v.id_venta
LEFT JOIN Clientes c ON v.id_cliente = c.id_cliente
WHERE f.fecha BETWEEN '2000-01-01' AND '2025-05-22'
GROUP BY f.tipo, CONCAT(MONTH(f.fecha), '-', YEAR(f.fecha)), c.id_cliente
ORDER BY CONCAT(MONTH(f.fecha), '-', YEAR(f.fecha)), f.tipo;

-- getEstadoSaludAnimales
SELECT e.nombre AS nombre_especie, r.nombre AS nombre_raza, a.estado_salud, 
       COUNT(*) AS cantidad_animales,
       ROUND((COUNT(*) * 100 / SUM(COUNT(*)) OVER (PARTITION BY e.id_especie, r.id_raza)), 2) AS porcentaje
FROM Animales a
INNER JOIN Razas r ON a.id_raza = r.id_raza
INNER JOIN Especies e ON r.id_especie = e.id_especie
WHERE a.activo = TRUE
GROUP BY e.id_especie, r.id_raza, a.estado_salud
ORDER BY e.nombre, r.nombre, a.estado_salud;

-- getExitoReproductivo
SELECT e.nombre AS nombre_especie, ra.nombre AS nombre_raza, 
       COUNT(*) AS total_gestaciones,
       SUM(CASE WHEN r.estado = 'Finalizado' AND r.numero_crias > 0 THEN 1 ELSE 0 END) AS partos_exitosos,
       ROUND(AVG(r.numero_crias), 2) AS promedio_crias,
       r.estado AS estado_gestacion
FROM Reproduccion r
INNER JOIN Animales a ON r.id_animal_hembra = a.id_animal
INNER JOIN Razas ra ON a.id_raza = ra.id_raza
INNER JOIN Especies e ON ra.id_especie = e.id_especie
GROUP BY e.id_especie, ra.id_raza, r.estado
ORDER BY total_gestaciones DESC;

-- getConsumoAlimentos
SELECT p.nombre AS nombre_producto, c.nombre AS nombre_corral, a.codigo AS codigo_animal, 
       COALESCE(ea.nombre, ec.nombre) AS nombre_especie, SUM(ca.cantidad) AS total_cantidad, 
       MAX(ca.fecha) AS ultima_fecha
FROM Consumo_Alimentos ca
INNER JOIN Productos_Inventario p ON ca.id_producto = p.id_producto
LEFT JOIN Animales a ON ca.id_animal = a.id_animal
LEFT JOIN Razas r ON a.id_raza = r.id_raza
LEFT JOIN Especies ea ON r.id_especie = ea.id_especie
LEFT JOIN Corrales c ON ca.id_corral = c.id_corral
LEFT JOIN Especies ec ON c.id_especie = ec.id_especie
WHERE ca.fecha BETWEEN '2000-01-01' AND '2025-05-22'
GROUP BY p.id_producto, c.id_corral, a.id_animal, COALESCE(ea.nombre, ec.nombre)
ORDER BY total_cantidad DESC;

-- getProduccionPorTipoCorral
SELECT p.tipo_producto, c.nombre AS nombre_corral, e.nombre AS nombre_especie, 
       SUM(p.cantidad) AS total_cantidad, p.unidad_medida
FROM Produccion p
LEFT JOIN Corrales c ON p.id_corral = c.id_corral
LEFT JOIN Especies e ON c.id_especie = e.id_especie
WHERE p.fecha BETWEEN '2000-01-01' AND '2025-05-22'
GROUP BY p.tipo_producto, c.id_corral, e.id_especie, p.unidad_medida
ORDER BY total_cantidad DESC;