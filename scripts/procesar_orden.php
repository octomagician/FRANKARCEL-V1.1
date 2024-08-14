<?php
include '../class/database.php';

$conexion = new database();

session_start();

header('Content-Type: application/json');

$response = array('success' => false, 'message' => 'Error desconocido.');

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (isset($_POST['cliente']) && isset($_POST['dispositivo']) && isset($_POST['servicio']) && isset($_POST['descripcion'])) {
        $mostrador = $_SESSION["usuario"];
        $cliente = $_POST['cliente'];
        $dispositivo = $_POST['dispositivo'];
        $servicio = $_POST['servicio'];
        $descripcion = $_POST['descripcion'];

        try 
        {
            $params = array
            (
                'mostrador' => $mostrador,
                'dispositivo' => $dispositivo,
                'servicio' => $servicio,
                'descripcion' => $descripcion
            );

            $resultado = $conexion->ejecutarProcedimiento('nueva_orden', $params);

            if ($resultado['status'] == 'success') 
            {
                $response['success'] = true;
                $response['message'] = 'La nueva orden ha sido creada exitosamente.';
            } 
            else 
            {
                $response['success'] = false;
                $response['message'] = 'Hubo un problema al generar la orden: ' . $resultado['message'];
            }
        } 
        catch (PDOException $e) 
        {
            $response['success'] = false;
            $response['message'] = 'Hubo un problema al generar la orden: ' . $e->getMessage();
            error_log($e->getMessage()); 
            
        }
    } 
    else 
    {
        $response['success'] = false;
        $response['message'] = 'No se recibieron todos los datos necesarios.';
    }
} 
else 
{
    $response['success'] = false;
    $response['message'] = 'Método de solicitud no válido.';
}

echo json_encode($response);
?>
