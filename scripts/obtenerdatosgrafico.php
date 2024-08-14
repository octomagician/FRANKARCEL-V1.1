<?php
session_start();
include '../class/database.php';

    $conexion = new database();
    $conexion->conectarDB();


    $consulta = "SELECT COUNT(numero_venta) as ventas, DATE_FORMAT(fecha, '%Y-%m') as mes FROM ventas GROUP BY mes";

$data = $conexion->seleccionar($consulta);

$conexion->desconectarDB();

echo json_encode($data);
?>
