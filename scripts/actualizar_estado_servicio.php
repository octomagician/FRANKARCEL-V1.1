<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../class/database.php';
$conexion = new database();
$conexion->conectarDB();

if (!isset($_POST['id_orden'])) 
{
    echo json_encode(['error' => 'id_orden no proporcionado']);
    exit;
}

$ordenId = $_POST['id_orden']; 
$params = ['ordenId' => $ordenId];

$historialQuery = "
    SELECT
        detalle_servicio.fecha,
        estado_servicio.estado AS estado,
        detalle_servicio.descripcion,
        detalle_servicio.precio
    FROM
        detalle_servicio
    INNER JOIN estado_servicio ON detalle_servicio.estado = estado_servicio.id_estado
    WHERE
        detalle_servicio.orden = :ordenId
    ORDER BY
        detalle_servicio.fecha DESC
";
$historialResult = $conexion->seleccionar($historialQuery, $params);

$historial = [];
if ($historialResult) 
{
    foreach ($historialResult as $historialRow) 
    {
        $historial[] = 
        [
            'fecha' => $historialRow->fecha,
            'estado' => $historialRow->estado,
            'descripcion' => $historialRow->descripcion,
            'precio' => $historialRow->estado === 'Terminado' ? $historialRow->precio : null
        ];
    }

    $estadoActual = $historial[0]['estado'] ?? '';
    $progress = 0;
    $imagen = 0;

    switch ($estadoActual) 
    {
        case 'En reparacion':
            $progress = 33;
            $imagen = 1;
            break;
        case 'Terminado':
            $progress = 66;
            $imagen = 2;
            break;
        case 'Entregado':
            $progress = 100;
            $imagen = 3;
            break;
        case 'Cancelado':
            $progress = rand(0, 66); // random
            $imagen = 4;
            break;
    }
} 
else 
{
    $progress = 0;
    $imagen = 0;
}

$jsonResponse = json_encode(['porcentaje' => $progress, 'historial' => $historial, 'imagen' => $imagen]);

if (json_last_error() !== JSON_ERROR_NONE) 
{
    echo json_last_error_msg();
    exit;
}

echo $jsonResponse;

$conexion->desconectarDB();
?>
