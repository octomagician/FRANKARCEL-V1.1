<?php
session_start();
include '../class/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $numero_venta = $_POST['numero_venta'];

    if ($numero_venta) 
    {
        $conexion = new database();
        $conexion->conectarDB();

        $consulta = "call ver_productos_venta($numero_venta)";
        $resultados = $conexion->seleccionar($consulta);

        if ($resultados !== false) 
        {
            foreach ($resultados as $producto) 
            {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($producto->id_producto) . "</td>";
                echo "<td>" . htmlspecialchars($producto->producto) . "</td>";
                echo "<td>" . htmlspecialchars($producto->cantidad) . "</td>";
                echo "<td>" . htmlspecialchars($producto->precio_unitario) . "</td>";
                echo "<td><button class='btn btn-danger btn-eliminar' data-id-producto='" . htmlspecialchars($producto->id_producto) . "'>Eliminar</button></td>";
                echo "</tr>";
            }
            
            $consulta_total = "call total_venta($numero_venta)";
            $precio_total_result = $conexion->seleccionar($consulta_total);
            if ($precio_total_result && count($precio_total_result) > 0) 
            {
                $precio_total = $precio_total_result[0]->precio;
                echo "<tr>";
                echo "<td colspan='3'></td>";
                echo "<td><strong>Total:</strong></td>";
                echo "<td>" . htmlspecialchars($precio_total) . "</td>";
                echo "</tr>";
            }
        } 
        else 
        {
            echo "<tr><td colspan='5'>Error al obtener los productos de la venta.</td></tr>";
        }
    } 
    else 
    {
        echo "<tr><td colspan='5'>Número de venta no proporcionado.</td></tr>";
    }
} 
else 
{
    echo "<tr><td colspan='5'>Método no permitido.</td></tr>";
}
?>
