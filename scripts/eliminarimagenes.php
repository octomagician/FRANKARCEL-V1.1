<?php
include '../class/database.php';

$conexion = new database();

$conexion->conectarDB();
$padonde = $_POST['padonde'];


if($padonde == 'Carrusel')
{
    $producto = $conexion->seleccionar("SELECT id_producto FROM productos WHERE nombre_producto = :nombre", ['nombre' => $nombre_producto]);         
    $resultado = $conexion->seleccionar("UPDATE productos set img = NULL WHERE id_producto = $producto");


  
        if ($resultado['status'] == 'success') 
        {
            $response['status'] = 'success';
            $response['message'] = 'Imagen insertada correctamente en carrusel.';
        } 
        else 
        {
            $response['status'] = 'error';
            $response['message'] = 'Error al insertar la imagen: ' . htmlspecialchars($resultado['message'], ENT_QUOTES, 'UTF-8');
        }
  

}



$conexion->desconectarDB();
echo json_encode($response);
?>