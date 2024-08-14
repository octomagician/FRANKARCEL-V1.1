<?php
include '../class/database.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$p_orden = $input['orden'];

$conexion = new database();
$conexion->conectarDB();

$consulta = "call identificar_estados(:orden)";
$params = ['orden' => $p_orden];

$result = $conexion->seleccionar($consulta, $params);

if ($result !== false) 
{
    echo json_encode($result);
} 
else 
{
    echo json_encode(['error' => $conexion->getError()]);
}
?>
