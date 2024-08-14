<?php
session_start();
include '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $numero_venta = $_POST['numero_venta'];
    $id_producto = $_POST['id_producto'];

    if ($numero_venta && $id_producto) 
    {
        $consulta = "call eliminar_producto_venta('$numero_venta', '$id_producto')";
        $conexion = new database();
        $resultados = $conexion->seleccionar($consulta);

        if ($resultados !== false) 
        {
            echo json_encode(['success' => true]);
        } 
        else 
        {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto de la venta.']);
        }
    } 
    else 
    {
        echo json_encode(['success' => false, 'error' => 'Número de venta o ID de producto no proporcionado.']);
    }
} 
else 
{
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
?>
