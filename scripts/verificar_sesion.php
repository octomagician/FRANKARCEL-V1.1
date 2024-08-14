<?php
include '../class/database.php';
$conexion = new database();
$conexion->conectarDB();
extract($_POST);

if ($conexion->verificar($usuario, $contrasena)) 
{
    echo 
    "
      <script>
        alert('Inicio de sesión exitoso.');
        window.location.reload();
      </script>
    ";
} 
else 
{
    echo 
    "
      <script>
        alert('Error en el inicio de sesión. Por favor, verifique sus credenciales.');
        window.history.back();
      </script>";
}
exit();
?>
