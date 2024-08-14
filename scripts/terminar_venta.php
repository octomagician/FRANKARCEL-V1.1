<?php
session_start();
include '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero_venta'])) 
{
    $numero_venta = intval($_POST['numero_venta']); 

    $conexion = new database();

    $params = ['p_numero_venta' => $numero_venta];
    $resultado = $conexion->ejecutarProcedimiento('terminar_venta_mostrador', $params);

    if ($resultado) 
    {
        echo json_encode(['success' => true, 'message' => 'Venta terminada exitosamente.']);
    } 
    else 
    {
        echo json_encode(['success' => false, 'message' => 'Error al terminar la venta: ' . $conexion->getError()]);
    }    
} 
else 
{
    echo "Método de solicitud no válido.";
}
?>
