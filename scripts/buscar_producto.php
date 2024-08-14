<?php
include '../class/database.php';

$conexion = new database();

if (isset($_GET['term'])) 
{
    $buscar = $_GET['term'];
    $consulta = "select producto from vw_productos where producto like :term";
    $params = ['term' => '%' . $buscar . '%'];
    
    $resultados = $conexion->ejecutarProcedimiento($consulta, $params);
    
    $productList = [];
    if ($resultados) 
    {
        foreach ($resultados as $row) 
        {
            $productList[] = $row->producto;
        }
    }
    
    echo json_encode($productList);
}
?>
