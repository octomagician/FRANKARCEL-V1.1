<?php
require_once '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoria'])) 
{
    $categoria = $_POST['categoria'];
    $conexion = new database();
    
    $consulta = "CALL productos_categoria('$categoria')";
    $productos = $conexion->seleccionar($consulta);
    
    foreach ($productos as $producto) 
    {
        echo "<option value='" . htmlspecialchars($producto->id_producto, ENT_QUOTES, 'UTF-8') . "'>";
        echo htmlspecialchars($producto->nombre_producto, ENT_QUOTES, 'UTF-8') . ": ";
        
        if ($producto->ram) 
        {
            echo "RAM (" . htmlspecialchars($producto->ram, ENT_QUOTES, 'UTF-8') . "GB) ";
        }
        if ($producto->almacenamiento) 
        {
            echo "Almacenamiento (" . htmlspecialchars($producto->almacenamiento, ENT_QUOTES, 'UTF-8') . "GB) ";
        }
        if ($producto->color) 
        {
            echo "Color (" . htmlspecialchars($producto->color, ENT_QUOTES, 'UTF-8') . ") ";
        }
        if ($producto->tamano) 
        {
            echo "Tamaño (" . htmlspecialchars($producto->tamano, ENT_QUOTES, 'UTF-8') . "in) ";
        }
        if ($producto->material) 
        {
            echo "Material (" . htmlspecialchars($producto->material, ENT_QUOTES, 'UTF-8') . ") ";
        }
        if ($producto->compatibilidad) 
        {
            echo "Compatibilidad (" . htmlspecialchars($producto->compatibilidad, ENT_QUOTES, 'UTF-8') . ") ";
        }
        echo "</option>";
    }
}
?>
