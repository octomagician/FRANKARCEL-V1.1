<?php
session_start();
include '../class/database.php';

$conexion = new database();
$conexion->conectarDB();

$consulta = 
"SELECT 
    COUNT(orden) AS Servicios_completados,
    DATE_FORMAT(fecha, '%Y-%m') AS mes
FROM 
    detalle_servicio
INNER JOIN 
    estado_servicio ON estado_servicio.id_estado = detalle_servicio.estado
WHERE 
    estado_servicio.estado = 'Entregado'
GROUP BY 
    mes
ORDER BY 
    mes";
;

$data = $conexion->seleccionar($consulta);

$conexion->desconectarDB();

echo json_encode($data);
?>
