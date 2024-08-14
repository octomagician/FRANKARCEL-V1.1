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

$tunas = false;

if ($user == 'tunas') 
{
    $tunas = true;
}

if (!$tunas) 
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
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0; /* Color de fondo opcional */
        }

        .rotate-on-hover {
            transition: transform 0.5s ease-in-out;
        }

        .rotate-on-hover:hover {
            animation: continuous-rotation 2s linear infinite;
        }

        @keyframes continuous-rotation {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <title>Continuous Rotation on Hover</title>
</head>
<body>
    <img src="furina.webp" alt="mi vieja" class="rotate-on-hover">
</body>
</html>
