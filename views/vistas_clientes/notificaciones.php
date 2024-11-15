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
    <title>Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
            color: #fff;
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
        .fixed-button-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        .whatsapp-button {
            background-color: #25D366;
            border: none;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .whatsapp-button:hover {
            background-color: #20c35b;
        }
        .whatsapp-button img {
            width: 20px;
            height: 20px;
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
            width: 18px;
        }
        .btn-icono2{
            display: none;
            width: 18px;
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
                    .custom-table {
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
                background-color: #000;
                color: #fff; 
            }

            .custom-table tbody tr:nth-child(even) {
                background-color: #000;
            }

            .custom-table tbody tr:nth-child(odd) {
                background-color: #000;
            }

            .custom-table .text-center {
                text-align: center;
                background-color: #000; 
            }
        p{
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top sticky-sm-top sticky-lg-top">
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
                                <a href="./carrito.php">
                                    <img src="https://thumbs.dreamstime.com/b/icono-de-las-compras-del-carro-la-compra-en-fondo-oscuro-116659167.jpg" alt="Carro de compras" style="height: 40px; margin-right: 5px;">
                                </a>
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
                                                default: break;
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
                    else 
                    {
                        echo '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" style="white-space: nowrap">Iniciar sesión</button>';
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Iniciar sesión</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="../../scripts/verificar_sesion.php" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Nombre de usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="mb-3">
                            <input type="submit" name="login" id="login" class="btn btn-success form-control" value="Iniciar sesión">
                        </div>
                    </form>
                    <div class="container">
                        <p>Aun no tienes cuenta? <a href="./registro.php">Crea una</a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() 
        {
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', function(event) 
            {
                event.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, 
                {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => 
                {
                    if (data.includes('Inicio de sesión exitoso.')) 
                    {
                        Swal.fire
                        ({
                            icon: 'success',
                            title: 'Inicio de sesión exitoso.',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        .then(() => 
                        {
                            window.location.reload();
                        });
                    } 
                    else 
                    {
                        Swal.fire
                        ({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error en el inicio de sesión. Por favor, verifique sus credenciales.'
                        });
                        form.reset();
                    }
                })
                .catch(error => 
                {
                    console.error('Error:', error);
                });
            });
        });
    </script>

    <div class="container mt-3" style="display: flex; justify-content: center; align-items: center;">
        <h1 style="font-size: 64px; margin: auto; text-align: center;" class="mt-3 mb-3">Notificaciones</h1>
    </div>

    <div class="container mb-3 mt-3">
        <!--Quite la variable table, para el diseño-->
        <table class="table-striped table-hover table-responsive custom-table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"><img src="../../img/campana2.png" alt="" class="btn-icono">NOTIFICACIÓN</th>
                    <th scope="col"><img src="../../img/calendario.png" alt="" class="btn-icono">FECHA</th>
                </tr>
            </thead>
            <tbody>
            <?php
                    $cliente = $_SESSION['usuario'];
                    $consulta = "call ver_notificaciones('$cliente')"; 

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
                            echo '<tr><td colspan="2" class="text-center">No se encontraron notificaciones</td></tr>';
                        }
                    } 
                    else 
                    {
                        echo '<tr><td colspan="2" class="text-center">Error al recuperar las notificaciones</td></tr>';
                    }
                ?>
            </tbody>
        </table>
        <br>
        <p>En esta sección, recibirá todas sus notificaciones importantes y actualizaciones relevantes. Es fundamental que la revise con regularidad para mantenerse al tanto de los adelantos y progreso de sus servicios, así como de cualquier otra información esencial que pueda requerir su atención. Aquí se le informará sobre cambios en el estado de sus ordenes de reparación, actualizaciones de nuestros productos y servicios, y cualquier otra comunicación relevante para su experiencia con nosotros. No pierda de vista esta sección para asegurarse de no perderse ninguna novedad importante o mensaje que pueda impactar en su cuenta o en sus servicios.</p>
    </div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
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