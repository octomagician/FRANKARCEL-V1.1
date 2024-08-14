<?php
include '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $id = $_POST['id'];
    $nv = $_POST['nv']; 

    $conexion = new Database();
    $conexion->conectarDB();

    $consulta = $conexion->seleccionar("call eliminar_producto_carrito($nv, $id)");

    if ($consulta !== false) 
    {
        $success = 'true';
    } 
    else 
    {
        $success = 'false';
    }

    $conexion->desconectarDB();
    header('Location: ../views/vistas_clientes/carrito.php?success=' . $success); 
    exit();
}
?>
