<?php
session_start();

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

$esAdministrador = false;
if ($roles) {
    foreach ($roles as $rol) {
        if ($rol->rol == 'Administrador') {
            $esAdministrador = true;
            break;
        }
    }
}

if (!$esAdministrador) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        .table_wrapper {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
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
                <a href="./admin_control_imagen.php">Gestion de imagenes</a>
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

    <div class="main-content">
        <h2 style="margin-top: 35px;">Control de ventas</h2>
        <div class="mb-3">
            <button type="button" class="btn btn-success mb-3" onclick="window.location.href='./registrar_venta.php'">Registrar nueva venta</button>
            <button type="button" class="btn btn-success mb-3" onclick="window.location.href='./completar_venta_carrito.php'">Completar venta de carrito</button>
        </div>
        <div class="mb-3">
            <form id="filterForm" action="" method="POST">
                <div class="row">
                    <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                            <label for="numero_venta" class="form-label">Buscar por numero</label>
                            <input type="number" name="numero_venta" id="numero_venta" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                            <label for="filtro_empleado" class="form-label">Buscar por empleado</label>
                            <select name="filtro_empleado" id="filtro_empleado" class="form-select">
                                <option value="">Seleccione empleado</option>
                                <?php
                                    $consulta = "select * from empleados_mostrador";
                                    $empleados = $conexion->seleccionar($consulta);

                                    foreach ($empleados as $empleado) {
                                        echo '<option value="' . htmlspecialchars($empleado->id_empleado) . '">' . htmlspecialchars($empleado->empleado) . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                            <label for="filtro_precio" class="form-label">Buscar por precios</label>
                            <select name="filtro_precio" id="filtro_precio" class="form-select">
                                <option value="">Seleccione precio</option>
                                <option value="mayor_menor">Mayor a menor</option>
                                <option value="menor_mayor">Menor a mayor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                            <label for="filtro_tipo" class="form-label">Filtrar por tipo</label>
                            <select name="filtro_tipo" id="filtro_tipo" class="form-select">
                                <option value="">Seleccione tipo de venta</option>
                                <option value="pedido">Por pedido</option>
                                <option value="mostrador">En mostrador</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <input type="submit" value="Filtrar venta" class="btn btn-success">
                </div>
            </form>
        </div>
        <div class="mb-3 table_wrapper">
            <table class="table table-hover">
                <thead class="table-success">
                    <tr>
                        <th>Numero</th>
                        <th>Empleado</th>
                        <th>Productos</th>
                        <th>Tipo</th>
                        <th>Precio venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $consulta = "select * from ventas_completadas";

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
                        {
                            if (!empty($_POST['numero_venta'])) 
                            {
                                $numero_venta = $_POST['numero_venta'];
                                $consulta = "call cv_numero_venta ($numero_venta)";
                            } 
                            elseif (!empty($_POST['filtro_empleado'])) 
                            {
                                $filtro_empleado = $_POST['filtro_empleado'];
                                $consulta = "call buscar_ventas_empleado ($filtro_empleado)";
                            } 
                            elseif (!empty($_POST['filtro_precio'])) 
                            {
                                $filtro_precio = $_POST['filtro_precio'];

                                if ($filtro_precio == "mayor_menor") 
                                {
                                    $consulta = "select * from ventas_completadas_mayor_menor";
                                } 
                                elseif ($filtro_precio == "menor_mayor") 
                                {
                                    $consulta = "select * from ventas_completadas_menor_mayor";
                                }
                            } 
                            elseif (!empty($_POST['filtro_tipo'])) 
                            {
                                $filtro_tipo = $_POST['filtro_tipo'];

                                if ($filtro_tipo == "pedido") 
                                {
                                    $consulta = "select * from ventas_pedido";
                                } 
                                elseif ($filtro_tipo == "mostrador") 
                                {
                                    $consulta = "select * from ventas_mostrador";
                                }
                            }
                        }

                        $ventas = $conexion->seleccionar($consulta);

                        foreach ($ventas as $venta) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($venta->numero) . '</td>';
                            echo '<td>' . htmlspecialchars($venta->empleado) . '</td>';
                            echo '<td>';
                            $consulta_productos = "call buscar_productos_venta ($venta->numero)";
                            $productos = $conexion->seleccionar($consulta_productos);
                            echo '<ul style="list-style-type: none;">';
                            foreach ($productos as $producto) {
                                echo '<li>' . htmlspecialchars($producto->producto) . '</li>';
                            }
                            echo '</ul>';
                            echo '</td>';
                            echo '<td>' . htmlspecialchars($venta->tipo) . '</td>';
                            echo '<td>$' . htmlspecialchars($venta->precio) . '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const numeroVentaInput = document.getElementById('numero_venta');
            const filtroEmpleadoSelect = document.getElementById('filtro_empleado');
            const filtroPrecioSelect = document.getElementById('filtro_precio');
            const filtroTipoSelect = document.getElementById('filtro_tipo');

            function disableOtherFilters(except) {
                const filters = [numeroVentaInput, filtroEmpleadoSelect, filtroPrecioSelect, filtroTipoSelect];
                filters.forEach(filter => {
                    if (filter !== except) {
                        filter.disabled = true;
                    }
                });
            }

            function enableAllFilters() {
                const filters = [numeroVentaInput, filtroEmpleadoSelect, filtroPrecioSelect, filtroTipoSelect];
                filters.forEach(filter => {
                    filter.disabled = false;
                });
            }

            numeroVentaInput.addEventListener('input', function() {
                if (this.value) {
                    disableOtherFilters(this);
                } else {
                    enableAllFilters();
                }
            });

            filtroEmpleadoSelect.addEventListener('change', function() {
                if (this.value) {
                    disableOtherFilters(this);
                } else {
                    enableAllFilters();
                }
            });

            filtroPrecioSelect.addEventListener('change', function() {
                if (this.value) {
                    disableOtherFilters(this);
                } else {
                    enableAllFilters();
                }
            });

            filtroTipoSelect.addEventListener('change', function() {
                if (this.value) {
                    disableOtherFilters(this);
                } else {
                    enableAllFilters();
                }
            });

            document.getElementById('filterForm').addEventListener('submit', function(e) {
                if (!numeroVentaInput.value && !filtroEmpleadoSelect.value && !filtroPrecioSelect.value && !filtroTipoSelect.value) {
                    e.preventDefault();
                    alert('Seleccione al menos un filtro para buscar ventas.');
                }
            });
        });
    </script>

    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>