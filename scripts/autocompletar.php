<?php
include '../class/database.php';

if (isset($_GET['query'])) 
{
    $query = $_GET['query'];
    $conexion = new database();
    $conexion->conectarDB();
    $consulta = "select nombre_usuario from usuario where nombre_usuario like '%$query%';";
    $usuarios = $conexion->seleccionar($consulta);
    echo json_encode($usuarios);
}
?>
