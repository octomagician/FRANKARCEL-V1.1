<?php
include '../class/database.php';

if (isset($_POST['orden'])) 
{
    $orden = $_POST['orden'];
    $database = new database();
    $database->conectarDB();

    $inicio = $database->seleccionar("call inicio_orden($orden)");
    
    $historial = $database->seleccionar("call historial_actualizaciones($orden)");

    $database->desconectarDB();

    $response = 
    [
        'inicio' => $inicio,
        'historial' => $historial
    ];

    echo json_encode($response);
}
?>
