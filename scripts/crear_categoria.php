<?php
include '../class/database.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$p_nombre_categoria = filter_var($data['p_nombre_categoria'], FILTER_SANITIZE_STRING);
$p_ram = filter_var($data['p_ram'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
$p_almacenamiento = filter_var($data['p_almacenamiento'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
$p_color = filter_var($data['p_color'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
$p_tamano = filter_var($data['p_tamano'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
$p_material = filter_var($data['p_material'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
$p_compatibilidad = filter_var($data['p_compatibilidad'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

$params = 
[
    'p_nombre_categoria' => $p_nombre_categoria,
    'p_ram' => $p_ram,
    'p_almacenamiento' => $p_almacenamiento,
    'p_color' => $p_color,
    'p_tamano' => $p_tamano,
    'p_material' => $p_material,
    'p_compatibilidad' => $p_compatibilidad
];

$conexion = new database();
$response = $conexion->ejecutarProcedimiento('crear_categoria', $params);

if ($response["status"] === "error" && strpos($response["message"], 'La categoría ya existe') !== false) 
{
    $response = ["status" => "error", "message" => "La categoría ya existe. Por favor, elige otro nombre."];
}

header('content-type: application/json');
echo json_encode($response);
?>
