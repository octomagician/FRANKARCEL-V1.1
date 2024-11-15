<?php
session_start();
include '../../class/database.php';
$conexion = new database();
$conexion->conectarDB();



//ola

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
    header("Location: ../../index.php");
    exit();
}














$usuario = $_SESSION['usuario'];

$ordenesQuery = "
    SELECT 
        orden.id_orden,
        CONCAT(marca.marca, ' ', dispositivo.modelo) AS nombre_dispositivo
    FROM 
        orden
    INNER JOIN 
        dispositivo ON orden.dispositivo = dispositivo.id_dispositivo
    INNER JOIN 
        marcas marca ON dispositivo.marca = marca.id_marca
";
$ordenesResult = $conexion->seleccionar($ordenesQuery);

$ordenes = [];
if ($ordenesResult) {
    foreach ($ordenesResult as $ordenRow) {
        $ordenes[] = [
            'id_orden' => $ordenRow->id_orden,
            'nombre_dispositivo' => $ordenRow->nombre_dispositivo
        ];
    }
}

$conexion->desconectarDB();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progreso orden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto; 
            width: 100%;
        }
        .progress {
            height: 30px;
        }
                .progress-bar {
            font-size: 16px;
            line-height: 30px;
            background: linear-gradient(to right, #4caf50 0%, #f44336 100%);
        }
        .progress-images {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .progress-images img {
            width: 100px;
            height: 100px;
            filter: grayscale(100%) brightness(50%);
            transition: filter 0.3s;
        }
        .progress-images img.active {
            filter: grayscale(0%) brightness(100%);
        }
        body {
            font-family: 'Arial', sans-serif;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        h2 {
            font-size: 2rem;
            margin-top: 2rem;
        }

        .progress {
            height: 30px;
            margin-bottom: 1rem;
        }

        .progress-bar {
            font-size: 1rem;
            line-height: 30px;
            background: linear-gradient(to right, green, yellow, red);
        }

        .progress-images {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .progress-images img {
            width: 75px;
            height: 75px;
            filter: grayscale(100%) brightness(50%);
            transition: filter 0.3s;
        }

        .progress-images img.active {
            filter: grayscale(0%) brightness(100%);
        }

        #historial {
            list-style-type: none;
            padding-left: 0;
        }

        #historial li {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1rem;
            padding: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #historial li strong {
            display: block;
            font-size: 1.2rem;
            color: #333;
        }

        #historial li span {
            display: block;
            margin-top: 0.5rem;
            font-size: 1rem;
            color: #555;
        }
        .navbar {
            background-color: #000;
        }
        .navbar-nav {
            text-align: center;
            width: 100%;
        }
        .navbar-nav .nav-item {
            display: inline-block;
        }
        .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-nav .nav-link:hover {
            color: #ccc;
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
        body {
            background-color: #242424;
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
                                    <img src="../../img/carrito.png" alt="Carro de compras" style="height: 40px; margin-right: 5px;">
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

    <div class="container mt-5">
    <h1>Estado del Servicio</h1>
    <select id="ordenesSelect" class="form-control mb-3">
        <option value="">Seleccione una orden</option>
        <?php foreach ($ordenes as $orden): ?>
            <option value="<?= $orden['id_orden'] ?>"><?= $orden['nombre_dispositivo'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" id="ordenManualInput" class="form-control mb-3" placeholder="Introduce tu número de orden">
    <div class="d-flex justify-content-end">
        <button id="actualizarOrden" class="btn btn-success mb-3 me-2">Buscar orden</button>
        <button id="reiniciarOrden" class="btn btn-secondary mb-3">Reiniciar</button>
    </div>


    <div id="barraProgreso" class="progress mb-3">
        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <div class="progress-images">
        <img src="../../img/recibido.png" id="imagen1" alt="Paso 1">
        <img src="../../img/listo.jpg" id="imagen2" alt="Paso 2">
        <img src="../../img/7444305.png" id="imagen3" alt="Paso 3">
        <img src="../../img/cancelado.png" id="imagen4" alt="Paso 4">
    </div>
    <h2 class="mt-3">Historial de Estado</h2>
    <ul id="historial"></ul>
</div>

<script>
    $(document).ready(function() 
    {
        $('#ordenesSelect').change(function() 
        {
            var ordenSeleccionada = $(this).val();
            actualizarEstadoOrden(ordenSeleccionada);
        });

        $('#actualizarOrden').click(function() 
        {
            var ordenManual = $('#ordenManualInput').val();
            if (ordenManual) 
            {
                actualizarEstadoOrden(ordenManual);
            } 
            else 
            {
                Swal.fire('Error', 'Por favor, introduce un número de orden.', 'error');
            }
        });

        $('#reiniciarOrden').click(function() 
        {
            reiniciarEstado();
        });

        function actualizarEstadoOrden(id_orden) 
        {
            $.ajax
            ({
                url: '../../scripts/actualizar_estado_servicio.php',
                type: 'POST',
                data: { id_orden: id_orden },
                success: function(response) {
                    var data;
                    try 
                    {
                        data = JSON.parse(response);
                    } 
                    catch (e) 
                    {
                        Swal.fire('Error', 'La respuesta del servidor no es válida.', 'error');
                        console.error('Invalid JSON response:', response);
                        return;
                    }

                    var progreso = data.porcentaje + '%';
                    $('#barraProgreso .progress-bar').css('width', progreso).text(progreso);

                    $('.progress-images img').removeClass('active');

                    if (data.porcentaje >= 33) 
                    {
                        $('#imagen1').addClass('active');
                    }
                    if (data.porcentaje >= 66) 
                    {
                        $('#imagen2').addClass('active');
                    }
                    if (data.porcentaje >= 100) 
                    {
                        $('#imagen3').addClass('active');
                    }

                    if (data.imagen === 4) 
                    {
                        $('#imagen4').addClass('active');
                    }

                    $('#historial').empty();
                    data.historial.forEach(function(item) 
                    {
                        $('#historial').append('<li><strong>Estado:</strong> ' + item.estado + '<span><strong>Fecha:</strong> ' + item.fecha + '</span><span><strong>Descripción:</strong> ' + item.descripcion + '</span><span><strong>Precio:</strong> ' + (item.precio ? item.precio : 'N/A') + '</span></li>');
                    });
                },
                error: function(xhr, status, error) 
                {
                    Swal.fire('Error', 'No se pudo obtener el estado de la orden.', 'error');
                }
            });
        }

        function reiniciarEstado() 
        {
            $('#ordenesSelect').val('');
            $('#ordenManualInput').val('');
            $('#barraProgreso .progress-bar').css('width', '0%').text('0%');
            $('.progress-images img').removeClass('active');
            $('#historial').empty();
        }
    });
</script>



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


    
    <div class="fixed-button-container">
        <button class="whatsapp-button" onclick="window.location.href='https://wa.me/1234567890'">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
        </button>
    </div>
    
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
