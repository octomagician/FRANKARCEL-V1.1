<?php
require_once '../class/database.php';

$conexion = new database();
$conexion->conectarDB();

header('Content-Type: application/json');

$orden = $_POST['orden'];

$consulta_inicio = "CALL inicio_orden(:orden)";
$params_inicio = ['orden' => $orden];
$inicio_result = $conexion->seleccionar($consulta_inicio, $params_inicio);

$response = 
[
    'inicio' => $inicio_result
];

echo json_encode($response);
?>
