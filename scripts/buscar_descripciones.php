<?php
include '../class/database.php';

if (isset($_POST['id_producto'])) 
{
    $id_producto = intval($_POST['id_producto']);
    $conexion = new database();
    $conexion->conectarDB();
    
    $consulta = "call buscar_descripciones(:id_producto)";
    $params = ['id_producto' => $id_producto];

    $resultado = $conexion->seleccionar($consulta, $params);
    
    if ($resultado && count($resultado) > 0) 
    {
        echo json_encode($resultado[0]);
    } 
    else 
    {
        echo json_encode(['descripcion_breve' => '', 'descripcion_larga' => '']);
    }
}
?>
