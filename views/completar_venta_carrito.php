<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit();
}

$databasePath = realpath(dirname(__FILE__) . '/../class/database.php');

if (file_exists($databasePath)) {
    include $databasePath;
} else {
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
        if ($rol->rol == 'Administrador' || $rol->rol == 'Mostrador') 
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
    <title>Completar carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .table_wrapper {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
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
        .form-control{
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }
        .form-control:focus{
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
    <div class="container mb-3 mt-3">
        <?php
            foreach ($roles as $rol) 
            {
                if ($rol->rol == 'Administrador') 
                {
                    echo '<button type="button" class="btn btn-success mb-3 mt-3" onclick="window.location.href=\'./admin_control_ventas.php\'">Volver</button>';
                    break;
                } 
                elseif ($rol->rol == 'Mostrador') 
                {
                    echo '<button type="button" class="btn btn-success mb-3 mt-3" onclick="window.location.href=\'./vista_empleado_mostrador.php\'">Volver</button>';
                    break;
                }
            }
        ?>
        <h1>Completar carrito</h1>
        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <form method="post" class="form">
                    <div class="mb-3">
                        <label for="numero_carrito" class="form-label">Número de carrito</label>
                        <input type="number" class="form-control" name="numero_carrito" id="numero_carrito" min="1" step="1" required>
                    </div>
                    <input type="submit" value="Buscar carrito" class="btn btn-success" name="buscar_carrito">
                </form>
            </div>
            <div class="col-lg-9 col-sm-12 mt-3 mb-3">
                <div class="table_wrapper">
                    <table class="table table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_carrito'])) {
                                $numero_carrito = $_POST['numero_carrito'];

                                $consulta = "call buscar_carrito($numero_carrito)";
                                $productos = $conexion->seleccionar($consulta);

                                if (empty($productos)) 
                                {
                                    echo '<script>Swal.fire("Error", "No se encontraron productos en el carrito.", "error");</script>';
                                } 
                                else 
                                {
                                    echo '<script>Swal.fire("Éxito", "Carrito encontrado exitosamente.", "success");</script>';
                                }

                                foreach ($productos as $producto) 
                                {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($producto->producto) . '</td>';
                                    echo '<td>' . htmlspecialchars($producto->cantidad) . '</td>';
                                    echo '<td>' . htmlspecialchars($producto->precio_unitario) . '</td>';
                                    echo '
                                        <td>
                                            <form method="post" class="form eliminar-producto-form">
                                                <input type="hidden" value="' . htmlspecialchars($producto->id) . '" name="borrar_id">
                                                <input type="hidden" value="' . htmlspecialchars($numero_carrito) . '" name="numero_carrito">
                                                <input type="submit" class="btn btn-danger" name="eliminar_producto" value="Eliminar">
                                            </form>
                                        </td>
                                    ';
                                    echo '</tr>';
                                }
                            }

                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_producto'])) 
                            {
                                $producto = $_POST['borrar_id'];
                                $numero_carrito = $_POST['numero_carrito'];

                                $consulta = "call eliminar_producto_venta($numero_carrito, $producto)";
                                $conexion->seleccionar($consulta);

                                echo '<script>Swal.fire("Éxito", "Producto eliminado del carrito.", "success");</script>';
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_carrito'])) 
                {
                    echo 
                    '
                        <div class="mb-3 mt-3 text-end">
                            <form action="" method="post" class="form">
                                <input type="hidden" name="terminar_carrito_numero" value="' . htmlspecialchars($numero_carrito) . '">
                                <input type="submit" value="Terminar venta" class="btn btn-danger" name="completar_carrito">
                            </form>
                        </div>
                    ';
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['completar_carrito'])) 
                {
                    $mostrador = $_SESSION['usuario'];
                    $terminar_carrito_numero = $_POST['terminar_carrito_numero'];

                    $resultado = $conexion->ejecutarProcedimiento('completar_carrito', [
                        'terminar_carrito_numero' => $terminar_carrito_numero,
                        'mostrador' => $mostrador
                    ]);

                    if ($resultado['status'] === 'success') {
                        echo '<script>Swal.fire("Éxito", "Venta completada exitosamente.", "success");</script>';
                    } else {
                        $error_message = $resultado['message'];
                        if (strpos($error_message, 'Cantidad en el carrito es mayor al stock disponible.') !== false) {
                            $user_friendly_message = 'La cantidad en el carrito es mayor al stock disponible.';
                        } else {
                            $user_friendly_message = 'Ocurrió un error al completar la venta.';
                        }
                        echo '<script>Swal.fire("Error", "' . addslashes($user_friendly_message) . '", "error");</script>';
                    }
                }


                ?>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>