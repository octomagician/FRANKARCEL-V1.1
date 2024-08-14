<?php
session_start();

$databasePath = realpath(dirname(__FILE__) . '/../class/database.php');

if (file_exists($databasePath)) 
{
    include $databasePath;
} 
else 
{
    die("Error: No se pudo encontrar el archivo database.php");
}

if (!class_exists('database')) {
    die("Error: La clase 'database' no se encuentra definida.");
}

$conexion = new database();
$conexion->conectarDB();

$user = $_SESSION["usuario"];

$consulta = "call roles_usuario('$user');";

$roles = $conexion->seleccionar($consulta);

$permiso = false;
if ($roles) 
{
    foreach ($roles as $rol) 
    {
        if ($rol->rol == 'Tecnico') 
        {
            $permiso = true;
            break;
        }
    }
}

if (!$permiso) 
{
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #242424;
        }
        h3{
            color: #96e52e;
        }
        p, h1, h2, h4, h6, label{
            color: #fff;
        }
        .btn-success{
            color: #000;
            background-color: #96e52e;    
            font-weight: bold;
            border-radius: 20px;
            font-size: 18px;
        }
        .btn-success:hover{
            color: #96e52e;
            background-color: #000;
            border: 1px solid #96e52e;
        }
        hr{
            border-bottom: 2px solid #fff;
        }
        .footer{
            background-color: #000;
        }
        .seccion{
            background-color: #000;
            border-radius: 20px;
        }
        .btn-icono{
            display: inline;
            width: 30px;
        }
        .btn-icono2{
            display: none;
            width: 30px;
        }
        .Iconos:hover .btn-icono{
            display: none;
        }
        .Iconos:hover .btn-icono2{
            display: inline;
        }
        .form-select{
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }
        .buscar-producto{
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }
        .buscar-producto:focus{
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }
        input[type="text"]::-webkit-input-placeholder {
            color: #bfbfbf;
        }
        .h5{
            color: #000;
        }
        .custom-table{
            width: 100%;
            border: 1px solid #fff;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            appearance: none;
            background-color: #000;
        }
        .custom-table thead {
            background-color: #96e52e;
            color: #000;
        }
        .custom-table th, .custom-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #fff;
        }
        .custom-table td{
            color: #fff;
        }
        .custom-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .custom-table .text-center {
            text-align: center;
            
        }
    </style>
</head>
<body>
    <div class="container d-flex flex-column align-items-center position-relative" style="height: 100vh;">
        <button type="button" class="btn btn-secondary position-absolute top-0 start-0 m-2 mt-3" onclick="window.location.href='./vista_empleado_tecnico.php'">Regresar</button>
        <h2 class="mb-4" style="margin-top: 75px;">Notificaciones</h2>
        <div class="mb-3">
            <table class="table table-striped">
                <thead>
                    <th>Notificacion</th>
                    <th>Fecha</th>
                </thead>
                <tbody>
                    <?php
                        $cliente = $_SESSION['usuario'];
                        $consulta = "call buscar_notificaciones_empleado('$cliente')"; 

                        $notificaciones = $conexion->seleccionar($consulta);

                        if ($notificaciones)
                        {
                            if (count($notificaciones) > 0) 
                            {
                                foreach ($notificaciones as $notificacion)
                                {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($notificacion->notificacion) . '</td>';
                                    echo '<td>' . htmlspecialchars($notificacion->fecha) . '</td>';
                                    echo '</tr>';
                                }
                            } 
                            else 
                            {
                                echo '<tr><td colspan="2">No se encontraron notificaciones</td></tr>';
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>