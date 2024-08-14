<?php
include '../class/database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'servicios' => []];

try 
{
    if (isset($_POST['tipo_servicio'])) {
        $tipo_servicio = $_POST['tipo_servicio'];
        $conexion = new database();
        $conexion->conectarDB();
        $consulta = "call buscar_servicios('$tipo_servicio');";
        $servicios = $conexion->seleccionar($consulta);

        if (!empty($servicios)) 
        {
            $response['success'] = true;
            $response['servicios'] = $servicios;
        } 
        else 
        {
            $response['message'] = 'No se encontraron servicios para el tipo de servicio especificado.';
        }
    } 
    else 
    {
        $response['message'] = 'Tipo de servicio no proporcionado.';
    }
} 
catch (Exception $e) 
{
    $response['message'] = 'Error al procesar la solicitud: ' . $e->getMessage();
}

echo json_encode($response);
?>
