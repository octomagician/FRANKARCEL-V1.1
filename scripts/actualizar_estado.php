<?php
include '../class/database.php';

$conexion = new database();
$conexion->conectarDB();

session_start();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$p_orden = $input['orden'];
$p_tecnico = $_SESSION["usuario"];
$p_estado = $input['estado'];
$p_descripcion = $input['descripcion'];
$p_precio = $input['precio'];

$consulta = "actualizar_estado"; 
$params = 
[
    'orden' => $p_orden,
    'tecnico' => $p_tecnico,
    'estado' => $p_estado,
    'descripcion' => $p_descripcion,
    'precio' => $p_precio
];

$result = $conexion->ejecutarProcedimiento($consulta, $params);

if ($result) 
{
    echo json_encode(['success' => true]);
} else 
{
    echo json_encode(['error' => $conexion->getError()]);
}
?>