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

if (!class_exists('database')) 
{
    die("Error: La clase 'database' no se encuentra definida.");
}

$conexion = new database();
$conexion->conectarDB();

$user = $_SESSION["usuario"];

$consulta = "call roles_usuario('$user');";
$roles = $conexion->seleccionar($consulta);

$admin = false;

if ($roles) 
{
    foreach ($roles as $rol) 
    {
        if ($rol->rol == 'Administrador') 
        {
            $admin = true;
            break;
        }
    }
}

if (!$admin) 
{
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (isset($_POST['add_service'])) 
    {
        $tipo_servicio = $_POST['tipo_servicio'];
        $servicio = $_POST['servicio'];
        $descripcion = $_POST['descripcion'];

        $consulta = "call nuevo_servicio('$tipo_servicio', '$servicio', '$descripcion');";
        $conexion->seleccionar($consulta);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } 
    elseif (isset($_POST['update_service'])) 
    {
        $nombre_servicio = $_POST['servicio_modificar'];
        $nuevo_nombre = $_POST['servicio'];
        $descripcion = $_POST['descripcion'];

        $consulta = "call editar_servicio('$nombre_servicio', '$nuevo_nombre', '$descripcion');";
        $conexion->seleccionar($consulta);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de imagenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar {
            border-right: 2px solid #96e52e;
            width: 250px;
            background-color: #000;
            padding: 15px;
            color: #96e52e;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -250px;
            transition: left 0.3s ease;
            z-index: 1000;
        }
        .sidebar a {
            color: #96e52e;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #96e52e;
            color: #000;
        }
        .sidebar .submenu {
            padding-left: 20px;
        }
        .main-content {
            padding: 20px;
        }
        .form-inline .btn {
            width: 175px;
        }
        .form-inline .form-control,
        .form-inline .form-select {
            flex: 1;
            margin-right: 10px;
        }
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
        .sidebar-open {
            left: 0;
        }
        .toggle-sidebar-btn {
            background-color: #7eb315;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        .hamburger-icon {
            display: inline-block;
            width: 20px;
            height: 2px;
            background-color: white;
            position: relative;
        }
        .hamburger-icon::before,
        .hamburger-icon::after {
            content: '';
            width: 20px;
            height: 2px;
            background-color: white;
            position: absolute;
            left: 0;
        }
        .hamburger-icon::before {
            top: -6px;
        }
        .hamburger-icon::after {
            bottom: -6px;
        }
        table {
        width: 100%;
        border-collapse: collapse;
        }

        td {
        word-break: break-all; 
        overflow-wrap: break-word; 
        }
        .toggle-sidebar-btn
        { 
            opacity: 0.3;


        }
        .toggle-sidebar-btn:hover
        { 
            transition: 0.4s;
            opacity: 1;


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
        .btn-danger{
            width: 18%;
            color: #fff;
            background-color: #cd240a;    
            font-weight: bold;
            border-radius: 20px;
            font-size: 18px;
        }
        .btn-danger:hover{
            color: #cd240a;
            background-color: #000;
            border: 1px solid #cd240a;
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
        input[type="search"]::-webkit-input-placeholder {
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

    <button class="toggle-sidebar-btn" id="toggleSidebarBtn">
        <div class="hamburger-icon"></div>
    </button>

    <div class="sidebar" id="sidebar">
        <button class="toggle-sidebar-btn" id="hideSidebarBtn">
            <div class="hamburger-icon"></div>
        </button>
        <h1 style="margin-top: 35px;">Dashboard</h1>
        <a href="./administrador.php">Estadísticas</a>
        <div>
            <a href="#" class="menu-item" data-bs-toggle="collapse" data-bs-target="#productosMenu" aria-expanded="false" aria-controls="productosMenu">Productos ▼</a>
            <div class="collapse submenu" id="productosMenu">
                <a href="./admin_gestion_productos.php">Inventario y gestión</a>
                <a href="./admin_control_ventas.php">Control de ventas</a>
                <a href="./admin_reabastecimiento.php">Reabastecimiento</a>
                <a href="./admin_control_imagen.php">Imagenes</a>

            </div>
        </div>
        <div>
            <a href="#" class="menu-item" data-bs-toggle="collapse" data-bs-target="#serviciosMenu" aria-expanded="false" aria-controls="serviciosMenu">Servicios ▼</a>
            <div class="collapse submenu" id="serviciosMenu">
                <a href="./admin_gestion_servicios.php">Gestión</a>
                <a href="./admin_ajustes_servicio.php">Ajustes a servicio</a>
            </div>
        </div>
        <a href="./admin_gestion_usuario.php">Usuarios</a>
        <a href="./admin_notificaciones.php">Notificaciones</a>
        <a href="../index.php" class="" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); width: 80%;">Volver al inicio</a>
    </div>

    <script>
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const hideSidebarBtn = document.getElementById('hideSidebarBtn');
        const sidebar = document.getElementById('sidebar');

        toggleSidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-open');
        });

        hideSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('sidebar-open');
        });
    </script>


    <div class="container" style="margin-top: 35px;">
        <h3>Ingresar imagen</h3>
        </form>
        <div class="mb-3">
            <label for="categoria_reabastecimiento" class="form-label">Categoría</label>
            <form action="" method="POST">
                <div class="d-flex">
                    <select name="categoria_reabastecimiento" id="categoria_reabastecimiento" class="form-select">
                        <option value="">Seleccionar categoría</option>
                        <?php
                            $db = new database();
                            $consulta = "SELECT categoria_producto.categoria AS cat FROM categoria_producto";
                            $categorias = $db->seleccionar($consulta);

                            foreach ($categorias as $categoria) {
                                $selected = (isset($_POST['categoria_reabastecimiento']) && $_POST['categoria_reabastecimiento'] == $categoria->cat) ? 'selected' : '';
                                echo "<option value='{$categoria->cat}' $selected>{$categoria->cat}</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" class="btn btn-success" value="Confirmar" style="margin-left: 10px;">
                </div>
            </form>
        </div>
        <div class="mb-3">
            <form action="../scripts/imagenes.php" method="POST"enctype="multipart/form-data">
                <label for="celular" class="form-label">Productos</label>
                <div class="d-flex flex-column">
                    <select name="celular" id="celular" class="form-select">
                        <option name="" value="">Seleccionar producto</option>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoria_reabastecimiento'])) {
                                $categoria_reabastecimiento = $_POST['categoria_reabastecimiento'];
                                
                                $productos = $db->seleccionar("CALL productos_categoria(:categoria)", ['categoria' => $categoria_reabastecimiento]);

                                $opciones = $db->seleccionar("CALL opciones_producto(:categoria)", ['categoria' => $categoria_reabastecimiento]);
                                $opciones = !empty($opciones) ? $opciones[0] : null;

                                if ($opciones) {
                                    foreach ($productos as $producto) {
                                        $selected = (isset($_POST['productos_reabastecimiento']) && $_POST['productos_reabastecimiento'] == $producto->id_producto) ? 'selected' : '';
                                        echo "<option name='" . htmlspecialchars($producto->id_producto, ENT_QUOTES, 'UTF-8') . "' $selected>";
                                        echo htmlspecialchars($producto->nombre_producto, ENT_QUOTES, 'UTF-8') . ": ";

                                        if ($opciones->ram == 1) {
                                            echo "RAM (" . htmlspecialchars($producto->ram, ENT_QUOTES, 'UTF-8') . "GB) ";
                                        }
                                        if ($opciones->almacenamiento == 1) {
                                            echo "Almacenamiento (" . htmlspecialchars($producto->almacenamiento, ENT_QUOTES, 'UTF-8') . "GB) ";
                                        }
                                        if ($opciones->color == 1) {
                                            echo "Color (" . htmlspecialchars($producto->color, ENT_QUOTES, 'UTF-8') . ") ";
                                        }
                                        if ($opciones->tamano == 1) {
                                            echo "Tamaño (" . htmlspecialchars($producto->tamano, ENT_QUOTES, 'UTF-8') . "in) ";
                                        }
                                        if ($opciones->material == 1) {
                                            echo "Material (" . htmlspecialchars($producto->material, ENT_QUOTES, 'UTF-8') . ") ";
                                        }
                                        if ($opciones->compatibilidad == 1) {
                                            echo "Compatibilidad (" . htmlspecialchars($producto->compatibilidad, ENT_QUOTES, 'UTF-8') . ") ";
                                        }

                                        echo "</option>";
                                    }
                                }
                            }
                        ?>
                        
                    </select>
                    <div class="mb-3">
                    <label for="formFile" class="form-label">Ingrese una imagen</label>
                    <input class="form-control" type="file" name="imagen">
                    </div>
                    <select name="padonde" id="padonde" class="form-select">
                        <option name="" value="">Seleccionar Vista</option>
                        <option name="" value="Principal">Principal</option>
                        <option name="" value="Carrusel">Carrusel</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-3">Insertar</button>
            </form>
        </div>
        <div class="container"">

<h3>Eliminar imagenes</h3>

<div class="mb-3">
    <label for="categoria_reabastecimiento" class="form-label">Selecciona la categoria de la imagen a eliminar</label>
    <form action="" method="POST">
        <div class="d-flex">
            <select name="categoria_reabastecimiento" id="categoria_reabastecimiento" class="form-select">
                <option value="">Seleccionar categoría</option>
                <?php
                    $db = new database();
                    $consulta = "SELECT categoria_producto.categoria AS cat FROM categoria_producto";
                    $categorias = $db->seleccionar($consulta);

                    foreach ($categorias as $categoria) {
                        $selected = (isset($_POST['categoria_reabastecimiento']) && $_POST['categoria_reabastecimiento'] == $categoria->cat) ? 'selected' : '';
                        echo "<option value='{$categoria->cat}' $selected>{$categoria->cat}</option>";
                    }
                ?>
            </select>
            <input type="submit" class="btn btn-success" value="Confirmar" style="margin-left: 10px;">
        </div>
    </form>
</div>

</div>

<div class="mb-3">
    <form action="../scripts/imagenes.php" method="POST"enctype="multipart/form-data">
        <label for="celular" class="form-label">Selecciona el producto al que quieras eliminar imagen</label>
        <div class="d-flex flex-column">
            <select name="celular" id="celular" class="form-select">
                <option name="" value="">Seleccionar producto</option>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoria_reabastecimiento'])) {
                        $categoria_reabastecimiento = $_POST['categoria_reabastecimiento'];
                        
                        $productos = $db->seleccionar("CALL productos_categoria(:categoria)", ['categoria' => $categoria_reabastecimiento]);

                        $opciones = $db->seleccionar("CALL opciones_producto(:categoria)", ['categoria' => $categoria_reabastecimiento]);
                        $opciones = !empty($opciones) ? $opciones[0] : null;

                        if ($opciones) {
                            foreach ($productos as $producto) {
                                $selected = (isset($_POST['productos_reabastecimiento']) && $_POST['productos_reabastecimiento'] == $producto->id_producto) ? 'selected' : '';
                                echo "<option name='" . htmlspecialchars($producto->id_producto, ENT_QUOTES, 'UTF-8') . "' $selected>";
                                echo htmlspecialchars($producto->nombre_producto, ENT_QUOTES, 'UTF-8') . ": ";

                                if ($opciones->ram == 1) {
                                    echo "RAM (" . htmlspecialchars($producto->ram, ENT_QUOTES, 'UTF-8') . "GB) ";
                                }
                                if ($opciones->almacenamiento == 1) {
                                    echo "Almacenamiento (" . htmlspecialchars($producto->almacenamiento, ENT_QUOTES, 'UTF-8') . "GB) ";
                                }
                                if ($opciones->color == 1) {
                                    echo "Color (" . htmlspecialchars($producto->color, ENT_QUOTES, 'UTF-8') . ") ";
                                }
                                if ($opciones->tamano == 1) {
                                    echo "Tamaño (" . htmlspecialchars($producto->tamano, ENT_QUOTES, 'UTF-8') . "in) ";
                                }
                                if ($opciones->material == 1) {
                                    echo "Material (" . htmlspecialchars($producto->material, ENT_QUOTES, 'UTF-8') . ") ";
                                }
                                if ($opciones->compatibilidad == 1) {
                                    echo "Compatibilidad (" . htmlspecialchars($producto->compatibilidad, ENT_QUOTES, 'UTF-8') . ") ";
                                }

                                echo "</option>";
                            }
                        }
                    }
                ?>
                
            </select>
            <br>
            <select name="padonde" id="padonde" class="form-select">
            <label for="Vista" class="form-label">Ahora la vista de donde quieras eliminar esta imagen</label>

                <option name="" value="">Seleccionar Vista</option>
                <option name="" value="Eliminar principal">Principal</option>
                <option name="" value="Eliminar carrusel">Carrusel</option>
            </select>
        </div>
        <button type="submit" id="eliminar" class="btn btn-danger mt-3">Borrar</button>
    </form>

    
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form[action="../scripts/imagenes.php"]').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            Swal.fire({
                                title: 'Éxito',
                                text: result.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = '../views/admin_control_imagen.php'; // Redirige si es necesario
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: result.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location = '../views/admin_control_imagen.php'; // Redirige si es necesario
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un problema al procesar la solicitud.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>



</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</html>