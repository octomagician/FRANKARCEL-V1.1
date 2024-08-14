<?php
require_once '../class/database.php';

if (isset($_POST['producto_id'])) 
{
    $producto_id = $_POST['producto_id'];
    
    $conexion = new database();
    $consulta = "call stock_producto($producto_id)";
    $resultado = $conexion->seleccionar($consulta);

    if (!empty($resultado)) 
    {
        echo $resultado[0]->stock;
    } 
    else 
    {
        echo '0';
    }
}
?>
