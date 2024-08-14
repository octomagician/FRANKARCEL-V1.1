<?php
session_start();
include '../class/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $user = $_SESSION["usuario"];
    $id_producto = $_POST['id_producto'];
    $cantidad = $_POST['cantidad'];

    $conexion = new database();
    $conexion->conectarDB();

    try 
    {
        $consulta = "CALL insertar_celular_carrito('$user', $id_producto, $cantidad);";
        $conexion->seleccionar($consulta);
        echo json_encode(['status' => 'success', 'message' => 'Producto agregado al carrito exitosamente']);
    } 
    catch (PDOException $e) 
    {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
