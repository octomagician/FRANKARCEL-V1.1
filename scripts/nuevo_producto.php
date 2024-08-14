<?php
require_once '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $categoria = $_POST['categoria_nuevo_producto'];
    $nombre_producto = $_POST['nombre_producto'];
    $marca_producto = $_POST['marca_producto'];
    $color_producto = !empty($_POST['color_producto']) ? $_POST['color_producto'] : NULL;
    $tamano_producto = !empty($_POST['tamano_producto']) ? $_POST['tamano_producto'] : NULL;
    $ram_producto = !empty($_POST['ram_producto']) ? $_POST['ram_producto'] : NULL;
    $almacenamiento_producto = !empty($_POST['almacenamiento_producto']) ? $_POST['almacenamiento_producto'] : NULL;
    $material_producto = !empty($_POST['material_producto']) ? $_POST['material_producto'] : NULL;
    $conectividad_producto = $_POST['compatibilidad_producto'];
    $precio_producto = $_POST['precio_producto'];
    $descripcion_corta = $_POST['descripcion_corta'];
    $descripcion_detallada = $_POST['descripcion_detallada'];

    $params = 
    [
        'p_producto' => $nombre_producto,
        'p_marca' => $marca_producto,
        'p_categoria' => $categoria,
        'p_color' => $color_producto,
        'p_tamano' => $tamano_producto,
        'p_ram' => $ram_producto,
        'p_almacenamiento' => $almacenamiento_producto,
        'p_material' => $material_producto,
        'p_precio' => $precio_producto,
        'p_conectividad' => $conectividad_producto,
        'p_db' => $descripcion_corta,
        'p_dl' => $descripcion_detallada
    ];

    try 
    {
        $conexion = new database();
        $resultado = $conexion->ejecutarProcedimiento('nuevo_producto', $params);

        if ($resultado["status"] === "success") 
        {
            echo "Producto creado con éxito.";
        } 
        else 
        {
            echo "Error al crear el producto. Por favor, inténtelo de nuevo.";
        }
    } 
    catch (PDOException $e) 
    {
        echo "Error al crear el producto. Por favor, inténtelo de nuevo.";
    }
}
?>
