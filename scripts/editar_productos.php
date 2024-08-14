<?php
require_once '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = $_POST['editar_id_producto'];
    $nombre_producto = $_POST['editar_nombre_producto'];
    $marca = $_POST['editar_marca'];
    $categoria = $_POST['editar_categoria'];
    $precio = $_POST['editar_precio'];
    $descripcion_breve = $_POST['editar_descripcion_breve'];
    $descripcion_larga = $_POST['editar_descripcion_larga'];
    $ram = $_POST['editar_ram'] ?? null;
    $almacenamiento = $_POST['editar_almacenamiento'] ?? null;
    $color = $_POST['editar_color'] ?? null;
    $tamano = $_POST['editar_tamano'] ?? null;
    $material = $_POST['editar_material'] ?? null;
    $compatibilidad = $_POST['editar_compatibilidad'] ?? null;

    $params = [
        'p_id_producto' => $id_producto,
        'p_nombre_producto' => $nombre_producto,
        'p_marca' => $marca,
        'p_categoria' => $categoria,
        'p_precio' => $precio,
        'p_descripcion_breve' => $descripcion_breve,
        'p_descripcion_larga' => $descripcion_larga,
        'p_ram' => $ram,
        'p_almacenamiento' => $almacenamiento,
        'p_color' => $color,
        'p_tamano' => $tamano,
        'p_material' => $material,
        'p_compatibilidad' => $compatibilidad,
    ];

    $conexion = new database();

    if ($conexion->ejecutarProcedimiento('editar_producto', $params)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conexion->getError()]);
    }
}
?>
