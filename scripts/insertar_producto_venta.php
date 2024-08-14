<?php
require_once '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $numero_venta = isset($_POST['numero_venta']) ? intval($_POST['numero_venta']) : 0;
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

    if ($numero_venta > 0 && $id_producto > 0 && $cantidad > 0) 
    {
        $conexion = new database();

        $params = 
        [
            'p_numero_venta' => $numero_venta,
            'p_id_producto' => $id_producto,
            'p_cantidad' => $cantidad
        ];

        $resultado = $conexion->ejecutarProcedimiento('insertar_producto_venta', $params);
        if ($resultado['status'] === 'success') 
        {
            echo json_encode(['success' => true]);
        } 
        else 
        {
            echo json_encode(['success' => false, 'error' => $resultado['message']]);
        }
    } 
    else 
    {
        echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
    }
} 
else 
{
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
?>
