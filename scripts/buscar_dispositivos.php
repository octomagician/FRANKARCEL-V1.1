<?php
include '../class/database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'dispositivos' => []];

try 
{
    if (isset($_POST['usuario'])) 
    {
        $usuario = $_POST['usuario'];
        $conexion = new database();
        $conexion->conectarDB();
        $consulta = "call buscar_dispositivos('$usuario');";
        $dispositivos = $conexion->seleccionar($consulta);

        if (!empty($dispositivos)) 
        {
            $response['success'] = true;
            $response['dispositivos'] = $dispositivos;
        } 
        else 
        {
            $response['message'] = 'No se encontraron dispositivos para el usuario.';
        }
    } 
    else 
    {
        $response['message'] = 'Usuario no proporcionado.';
    }
} 
catch (Exception $e) 
{
    $response['message'] = 'Error al procesar la solicitud: ' . $e->getMessage();
}

echo json_encode($response);
?>
