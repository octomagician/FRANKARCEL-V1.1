<?php
session_start();
include './class/database.php';
$conexion = new database();
$conexion->conectarDB();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frankarcell</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
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
        p, h1, h2, h4, h5, h6{
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
        .modal-header{
            background-color: #000;
        }
        .modal-body{
            background-color: #242424;
            color: #fff;
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
            color: #fff;
        }
        input[type="password"]::-webkit-input-placeholder {
            color: #fff;
        }
        a{
            color: #96e52e;
            text-decoration-color: #96e52e;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top sticky-sm-top sticky-lg-top">
        <div class="container-fluid">
            <img class="logo" src="img/logo.png" alt="">
            <button data-bs-theme="dark" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #ccc;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./views/vistas_clientes/catalogo_celulares.php">Celulares</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./views/vistas_clientes/catalogo_accesorios.php">Accesorios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./views/vistas_clientes/servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./views/vistas_clientes/acerca_de.php">Acerca de</a>
                    </li>
                </ul>
                
                <?php
                    if (isset($_SESSION["usuario"])) 
                    {
                        echo '
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 d-flex justify-content-start align-items-center">
                                <a href="./views/vistas_clientes/carrito.php">
                                    <img src="img/carrito.png" alt="Carro de compras" style="height: 40px; margin-right: 5px;">
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
                                                    echo '<li><a class="dropdown-item" href="./views/administrador.php">Administrador</a></li>';
                                                    break;
                                                case 'Tecnico':
                                                    echo '<li><a class="dropdown-item" href="./views/vista_empleado_tecnico.php">Tecnico</a></li>';
                                                    break;
                                                case 'Mostrador':
                                                    echo '<li><a class="dropdown-item" href="./views/vista_empleado_mostrador.php">Mostrador</a></li>';
                                                    break;
                                                case 'Cliente':
                                                    echo '<li><a class="dropdown-item" href="./views/vistas_clientes/mi_cuenta.php">Mi cuenta</a></li>';
                                                    echo '<li><a class="dropdown-item" href="./views/vistas_clientes/notificaciones.php">Notificaciones</a></li>';
                                                    break;
                                            }
                                        }
                                    } 
                                    else 
                                    {
                                        echo '<li><a class="dropdown-item" href="#">No se encontraron roles</a></li>';
                                    }

                                    echo '
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="./scripts/cerrar_sesion.php">Cerrar sesión</a></li>
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
                    <form id="loginForm" action="./scripts/verificar_sesion.php" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Nombre de usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="mb-3" align="center">
                            <input type="submit" name="login" id="login" class="btn btn-success" value="Iniciar sesión">
                        </div>
                    </form>
                    <div class="container">
                        <p>Aun no tienes cuenta? <a href="./views/vistas_clientes/registro.php">Crea una</a> </p>
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
                        }).then(() => 
                        {
                            window.location.href = '/index.php';
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
    <!-- para que se muestre el modal cuando regrese de regisstrarse-->
    <script>
document.addEventListener("DOMContentLoaded", function() {
    const showModal = new URLSearchParams(window.location.search).get('showexampleModal');
    if (showModal === 'true') {
        const exampleModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        exampleModal.show();
    }
});
    
    </script>

    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/banner1.jpg" class="d-block w-100" alt="carrusel">
            </div>
            <div class="carousel-item">
                <img src="img/banner2.jpg" class="d-block w-100" alt="carrusel">
            </div>
            <div class="carousel-item">
                <img src="img/banner3.jpg" class="d-block w-100" alt="carrusel">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container mt-3" style="display: flex; justify-content: center; align-items: center;">
        <h1 style="font-size: 64px; text-align: center;">- Frankarcell -</h1>
        </div>
        
        <div class="container mt-3 mb-3">
        <div class="row mb-4 align-items-center seccion">
            <div class="col-lg-5 col-sm-12 d-flex flex-column align-items-center text-center">
                <h3>¡TENEMOS CELULARES PARA TI!</h3>
                <p>Accede nuestro catálogo y conoce los nuestros celulares con garantía y grandes ofertas.</p>
                <button class="btn btn-success" onclick="window.location.href='./views/vistas_clientes/catalogo_celulares.php'">
                    <div class="Iconos">
                        <img src="img/icn-cel.png" alt="" class="btn-icono">
                        <img src="img/icn-cel-hov.png" alt="" class="btn-icono2">
                        Celulares
                    </div>
                </button>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-5 col-sm-12 d-flex justify-content-center">
                <img src="./img/cel1.png" class="img-fluid mt-3 mb-3" alt="Celulares" style="width: 50%;">
            </div>
        </div>
        <hr>
        <div class="row mb-4 align-items-center seccion">
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <img src="./img/cel2.png"" class="img-fluid mt-3 mb-3" alt="Accesorios style="width: 50%;">
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-5 col-sm-12 d-flex flex-column align-items-center text-center">
                <h3>TENEMOS ACCESORIOS PARA TU CELULAR</h3>
                <p>¿Fundas? ¿Cargadores? Tenemos accesorios para tus dispositivos.</p>
                <button class="btn btn-success" onclick="window.location.href='./views/vistas_clientes/catalogo_accesorios.php'">
                    <div class="Iconos">
                        <img src="img/icn-acc.png" alt="" class="btn-icono">
                        <img src="img/icn-acc-hov.png" alt="" class="btn-icono2">
                        Accesorios
                    </div>
                </button>
            </div>
            <div class="col-sm-12 d-lg-none d-flex justify-content-center">
                <img src="./img/cel2.png"" class="img-fluid mt-3 mb-3" alt="Accesorios style="width: 50%;">
            </div>
        </div>
        <hr>
        <div class="row mb-4 align-items-center seccion">
            <div class="col-lg-5 col-sm-12 d-flex flex-column align-items-center text-center">
                <h3>REPARAMOS TUS DISPOSITIVOS</h3>
                <p>Puedes contar con nosotros para obtener servicios de soporte y mantenimiento de hardware y software. ¡Descubre más aquí!</p>
                <button class="btn btn-success" onclick="window.location.href='./views/vistas_clientes/servicios.php'">
                    <div class="Iconos">
                        <img src="img/icn-ser.png" alt="" class="btn-icono">
                        <img src="img/icn-ser-hov.png" alt="" class="btn-icono2">
                        Servicios
                    </div>
                </button>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-5 col-sm-12 d-flex justify-content-center">
                <img src="./img/cel3.png"" class="img-fluid mt-3 mb-3" alt="Dispositivos" style="width: 50%;" >
            </div>
        </div>
    </div>

    <div class="fixed-button-container">
        <button class="whatsapp-button" onclick="window.location.href='https://wa.me/1234567890'">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
        </button>
    </div>

    <footer class="footer" style="padding: 20px; margin-top: auto; background-color: #000; color: white; position: relative; bottom: 0; width: 100%;">
        <div class="container text-center">
            <h3 class="mb-3 mt-3" style="color: #96e52e;">Frankarcell</h3>
            <div class="d-flex justify-content-center mb-4">
                <a href="./views/vistas_clientes/catalogo_celulares.php" class="text-white mx-3" style="text-decoration: none;">
                    <i class="fas fa-mobile-alt"></i> Celulares
                </a>
                <a href="./views/vistas_clientes/servicios.php" class="text-white mx-3" style="text-decoration: none;">
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