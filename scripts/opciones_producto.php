<?php
include_once('../class/database.php');

if (isset($_POST['categoria'])) 
{
    $categoria = $_POST['categoria'];

    $db = new database();

    $consulta = "CALL opciones_producto(:categoria)";
    $params = array('categoria' => $categoria);
    
    $resultado = $db->seleccionar($consulta, $params);
    
    if ($resultado) 
    {
        echo json_encode($resultado[0]);
    } 
    else 
    {
        echo json_encode(['error' => 'No se encontraron resultados para la categoría proporcionada']);
    }
} 
else 
{
    echo json_encode(['error' => 'Categoría no proporcionada']);
}
?>
