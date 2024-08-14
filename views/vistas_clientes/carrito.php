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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #242424;
        }
        .footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto; 
            width: 100%;
        }
        .navbar {
            background-color: #000;
        }
        .navbar-nav {
            text-align: center;
            width: 100%;
            justify-content: center;
        }
        .navbar-nav .nav-item {
            display: inline-block;
        }
        .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-nav .nav-link:hover {
            color: #96e52e;
            border-bottom: 1px solid #96e52e;
        }
        .navbar-brand {
            color: #fff;
        }
        .navbar-brand:hover {
            color: #ccc;
        }
        .dropdown-menu {
            background-color: #000;
        }
        .dropdown-menu .dropdown-item {
            color: #fff;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #444;
        }
        .dropdown-menu .dropdown-item {
            color: #fff;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #444;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
        }
        .card-custom {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .card-body-custom {
            flex: 1; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-body-custom img {
            max-width: 100%;
            height: auto;
        }
        .thumbnail {
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        .thumbnail.active {
            opacity: 1;
        }
        .card-custom {
            min-height: 200px;
        }
        .collapse-container {
            width: 100%;
        }
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">Frankarcell</a>
            <button data-bs-theme="dark" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #ccc;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./catalogo_celulares.php">Celulares</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./catalogo_accesorios.php">Accesorios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./acerca_de.php">Acerca de</a>
                    </li>
                </ul>
                <?php
                    $conexion = new database();
                    $conexion->conectarDB();

                    if (isset($_SESSION["usuario"])) 
                    {
                        echo '
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 d-flex justify-content-start align-items-center">
                                <a href="#">
<img src="../../img/carrito.png" alt="Carro de compras" style="height: 40px; margin-right: 5px;">                                </a>
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="dropdown" style="margin-left: 10px;">
                                    <button class="btn btn-success dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Usuario: ' . htmlspecialchars($_SESSION["usuario"]) . '
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="userDropdown">';
                                    $user = $_SESSION["usuario"];
                                    $consulta = "call roles_usuario('$user');";
                                    $roles = $conexion->seleccionar($consulta);

                                    if ($roles) 
                                    {
                                        foreach ($roles as $rol) 
                                        {
                                            switch ($rol->rol) 
                                            {
                                                case 'Administrador':
                                                    echo '<li><a class="dropdown-item" href="../administrador.php">Administrador</a></li>';
                                                    break;
                                                case 'Tecnico':
                                                    echo '<li><a class="dropdown-item" href="../vista_empleado_tecnico.php">Tecnico</a></li>';
                                                    break;
                                                case 'Mostrador':
                                                    echo '<li><a class="dropdown-item" href="../vista_empleado_mostrador.php">Mostrador</a></li>';
                                                    break;
                                                case 'Cliente':
                                                    echo '<li><a class="dropdown-item" href="./mi_cuenta.php">Mi cuenta</a></li>';
                                                    echo '<li><a class="dropdown-item" href="./notificaciones.php">Notificaciones</a></li>';
                                                    break;
                                            }
                                        }
                                    } 
                                    else 
                                    {
                                        echo '<li><a class="dropdown-item" href="#">No roles found</a></li>';
                                    }

                                    echo '
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="../../scripts/cerrar_sesion.php">Cerrar sesión</a></li>
                                </ul>
                                </div>
                            </div>
                        </div>';
                    } 

                    echo '
                            </ul>
                        </div>
                    </div>
                    </div>';
                ?>
            </div>
        </div>
    </nav>

    <div class="container mb-3 mt-3">
        <h1 class="text-center" style="color: #fff;">Carrito de <?php echo htmlspecialchars($user); ?></h1>
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
                    $consulta = "call ver_carrito('$user')";
                    $productos = $conexion->seleccionar($consulta);
                    $precio = 0;
                    $numero_venta = null;

                    foreach ($productos as $producto) 
                    {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($producto->producto) . '</td>';
                        echo '<td>' . htmlspecialchars($producto->cantidad) . '</td>';
                        echo '<td>' . htmlspecialchars($producto->precio_unitario) . '</td>';
                        echo '<td>';
                        echo '<form class="form-inline" action="../../scripts/eliminar_producto_carrito.php" method="post">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($producto->id) . '">';
                        echo '<input type="hidden" name="nv" value="' . htmlspecialchars($producto->nv) . '">';
                        $numero_venta = $producto->nv;
                        echo '<input type="submit" class="btn btn-danger" value="Eliminar">';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                        $precio += $producto->precio_unitario * $producto->cantidad;
                    }
                    echo '<tr>';
                    echo '<td colspan="2" class="text-end"><strong>Total</strong></td>';
                    echo '<td colspan="2">$' . htmlspecialchars($precio) . '</td>';
                    echo '</tr>';
                ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            <button id="popoverButton" type="button" class="btn btn-success" data-bs-toggle="popover" data-bs-placement="left" data-bs-title="Popover title" data-bs-content="">Numero de carrito: <?php echo $numero_venta; ?></button>
           
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () 
            {
                const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
                const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));

                const button = document.getElementById('popoverButton');
                const popoverInstance = bootstrap.Popover.getInstance(button);
                
                const nvValue = '<?php echo $numero_venta; ?>';
                if (popoverInstance) 
                {
                    popoverInstance.setContent
                    ({
                        '.popover-body': nvValue
                    });
                }
            });
        </script>

    </div>

    <?php
        if (isset($_GET['success'])) 
        {
            $success = $_GET['success'] === 'true';
        
            if ($success) 
            {
                echo 
                '
                    <script>
                        Swal.fire({
                            icon: "success",
                            title: "Producto eliminado",
                            text: "El producto ha sido eliminado del carrito correctamente."
                        }).then(function() {
                            // Elimina el parámetro success de la URL
                            var url = new URL(window.location.href);
                            url.searchParams.delete("success");
                            window.history.replaceState({}, document.title, url.toString());
                        });
                        </script>
                ';
            } 
            else 
            {
                echo 
                '
                    <script>
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Hubo un problema al eliminar el producto del carrito."
                        }).then(function() {
                            // Elimina el parámetro success de la URL
                            var url = new URL(window.location.href);
                            url.searchParams.delete("success");
                            window.history.replaceState({}, document.title, url.toString());
                        });
                    </script>
                ';
            }
        }
    ?>


    <footer class="footer" style="padding: 20px; margin-top: auto; background-color: #000; color: white; position: relative; bottom: 0; width: 100%;">
        <div class="container text-center">
            <h3 class="mb-3 mt-3" style="color: #96e52e;">Frankarcell</h3>
            <div class="d-flex justify-content-center mb-4">
                <a href="./catalogo_celulares.php" class="text-white mx-3" style="text-decoration: none;">
                    <i class="fas fa-mobile-alt"></i> Celulares
                </a>
                <a href="./servicios.php" class="text-white mx-3" style="text-decoration: none;">
                    <i class="fas fa-tools"></i> Servicios
                </a>
                <a href="https://wa.me/1234567890" class="text-white mx-3" style="text-decoration: none;">
                    <i class="fab fa-whatsapp"></i> Contáctanos
                </a>
            </div>
            <p class="small mb-0">© 2024 Frankarcell. Todos los derechos reservados.</p>
        </div>
    </footer>


</body>
</html>