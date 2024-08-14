<?php
session_start();

$databasePath = realpath(dirname(__FILE__) . '/../../class/database.php');
if (file_exists($databasePath)) 
{
    include $databasePath;
}
else 
{
    die("Error: No se pudo encontrar el archivo database.php");
}

if (!class_exists('database')) 
{
    die("Error: La clase 'database' no se encuentra definida.");
}

$conexion = new database();
$conexion->conectarDB();

$user = $_SESSION["usuario"];

$consulta = "call roles_usuario('$user');";
$roles = $conexion->seleccionar($consulta);

$cliente = false;

if ($roles) 
{
    foreach ($roles as $rol) 
    {
        if ($rol->rol == 'Cliente') 
        {
            $cliente = true;
            break;
        }
    }
}

if (!$cliente) 
{
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        
        .card {
            margin: 15px;
            height: 100%;
            color: #fff;
            border-radius: 20px;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #000;
            border: 2px solid #96e52e;
            border-radius: 20px;
        }
        .btn-home {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .card img {
            max-width: 100px;
            height: auto;
            margin: 0 auto 15px;
        }
        body {
            background-color: #242424;
        }
        h3{
            color: #96e52e;
        }
        p, h1, h2, h4, h5, h6, label{
            color: #fff;
        }
        .logo {
            width:9%;
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
            border-bottom: 5px solid #fff;
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
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Mi cuenta</h1>
        <a href="../../index.php" class="btn btn-success btn-success">Volver al inicio</a>
        <div class="row">
            <div class="col-lg-6 col-sm-12 mb-3 mt-3">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="../../img/perfil_icono.png" class="mb-3" alt="Perfil">
                        <h5 class="card-title">Perfil</h5>
                        <p class="card-text">Gestiona tu información personal y de seguridad</p>
                        <a href="./perfil.php" class="btn btn-success">Ir a Perfil</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 mb-3 mt-3">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="../../img/carrito.png" class="mb-3" alt="Carrito de compras">
                        <h5 class="card-title">Carrito de compras</h5>
                        <p class="card-text">Revisa y edita los artículos en tu carrito</p>
                        <a href="./carrito.php" class="btn btn-success">Ir a Carrito</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 mb-3 mt-3">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="../../img/compras.png" class="mb-3" alt="Pedidos">
                        <h5 class="card-title">Historial de pedidos</h5>
                        <p class="card-text">Revisa las compras que hace realizado</p>
                        <a href="./historial_pedidos.php" class="btn btn-success">Ir a Pedidos</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 mb-3 mt-3">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="../../img/icn-ser-hov.png" class="mb-3" alt="Servicios">
                        <h5 class="card-title">Servicios</h5>
                        <p class="card-text">Revisa los servicios proporcionados a tus dispositivos</p>
                        <a href="./progreso_orden.php" class="btn btn-success">Ir a Servicios</a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
