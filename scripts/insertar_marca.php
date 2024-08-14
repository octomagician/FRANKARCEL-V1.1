<?php
require '../class/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $nueva_marca = $_POST['nueva_marca'];

    $db = new database();
    $params = ['p_nueva_marca' => $nueva_marca];
    $resultado = $db->ejecutarProcedimiento('insertar_marca', $params);

    if ($resultado['status'] === 'success') 
    {
        echo 'success';
    } 
    else 
    {
        echo 'error:Ocurrió un problema al insertar la marca. por favor, inténtelo de nuevo.';
    }
}
?>
