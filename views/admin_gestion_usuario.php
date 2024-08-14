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

$esAdministrador = false;
if ($roles) 
{
    foreach ($roles as $rol) 
    {
        if ($rol->rol == 'Administrador') 
        {
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
    <title>Gestion de usuarios</title>
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
        .autocomplete-items {
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            position: absolute;
            background-color: #fff;
            width: 93%;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-active {
            background-color: #e9e9e9;
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

    <div class="main-content" style="margin-top: 35px;">
        <h1>Gestión de usuarios</h1>
        <div class="mb-3">
            <form id="addOrderForm" method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Nombre de usuario</label>
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 mb-3">
                            <input id="search-box" type="text" name="usuario" class="form-control" placeholder="Nombre de Usuario" required>
                            <ul id="autocomplete-results" class="autocomplete-items"></ul>
                        </div>
                        <div class="col-lg-2 col-sm-12 mb-3">
                            <input type="submit" name="buscar_usuario" value="Buscar usuario" class="btn btn-success">
                        </div>
                    </div>
                </div>
            </form>

            <div class="table_wrapper">
                <table class="table-hover table">
                    <thead class="table-success">
                        <tr style="font-weight: bold;">
                            <td>Usuario</td>
                            <td>Nombre</td>
                            <td>Apellido paterno</td>
                            <td>Apellido materno</td>
                            <td>Telefono</td>
                            <td>Correo</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $consulta = "select * from empleados";
                            if (isset($_POST['buscar_usuario']))
                            {
                                $usuario = htmlspecialchars($_POST['usuario']);
                                $consulta = "call buscar_usuario ('$usuario')";
                            }

                            $usuarios = $conexion->seleccionar($consulta);
                            
                            foreach ($usuarios as $usuario)
                            {
                                echo '<tr>';
                                    echo '<td>' . htmlspecialchars($usuario->usuario) . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario->nombre) . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario->ap) . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario->am) . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario->telefono) . '</td>';
                                    echo '<td>' . htmlspecialchars($usuario->correo) . '</td>';

                                    $consulta = "call identificar_empleado('$usuario->id')";
                                    $empleados = $conexion->seleccionar($consulta);
                                    echo '<td>';

                                    foreach ($empleados as $empleado)
                                    {
                                        if ($empleado->emp > 0)
                                        {
                                            echo '<button type="button" style="margin-right: 10px; margin-bottom: 3px" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ag-' . htmlspecialchars($usuario->id) . '">Agregar roles</button>';
                                            echo '<button type="button" style="margin-right: 10px; margin-bottom: 3px" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#q-' . htmlspecialchars($usuario->id) . '">Quitar roles</button>';
                                        }
                                        else
                                        {
                                            echo '<button type="button" style="margin-right: 10px; margin-bottom: 3px" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reg-' . htmlspecialchars($usuario->id) . '">Registrar empleado</button>';
                                        }
                                    }
                                    
                                    echo '<button type="button" style="margin-right: 10px; margin-bottom: 3px" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dis-' . htmlspecialchars($usuario->id) . '">Asignar dispositivo</button>';
                                    echo '</td>';
                                echo '</tr>';
                            
                                echo 
                                '
                                <div class="modal fade" id="ag-' . htmlspecialchars($usuario->id) . '" tabindex="-1" aria-labelledby="ag-' . htmlspecialchars($usuario->id) . 'Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 style="color: #000;" class="modal-title fs-5" id="ag-' . htmlspecialchars($usuario->id) . 'Label">Asignar rol</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form" method="POST">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label" style="color: #000;">Usuario</label>
                                                        <input type="text" class="form-control" value="' . htmlspecialchars($usuario->usuario) . '" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="a_id_rol" class="form-label" style="color: #000;">Roles</label>
                                                        <input type="hidden" name="id_usuario" id="id_usuario" value="' . htmlspecialchars($usuario->id) . '">
                                                        <select name="a_id_rol" id="a_id_rol" class="form-select" required>
                                                            <option value="">Seleccione rol</option>';
                                                            
                                                            $consulta = "CALL no_rol($usuario->id)";
                                                            $roles = $conexion->seleccionar($consulta);
                                                            
                                                            foreach ($roles as $rol) {
                                                                echo '<option value="' . htmlspecialchars($rol->id_rol) . '">' . htmlspecialchars($rol->rol) . '</option>';
                                                            }
                                                        echo '
                                                        </select>
                                                    </div>
                                                    <button type="submit" name="asignar_rol" class="btn btn-success">Asignar rol</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            '; 

                            echo 
                                '
                                <div class="modal fade" id="q-' . htmlspecialchars($usuario->id) . '" tabindex="-1" aria-labelledby="q-' . htmlspecialchars($usuario->id) . 'Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 style="color: #000;" class="modal-title fs-5" id="ed-' . htmlspecialchars($usuario->id) . 'Label">Quitar rol</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form" method="POST">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label" style="color: #000;">Usuario</label>
                                                        <input type="text" class="form-control" value="' . htmlspecialchars($usuario->usuario) . '" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="q_id_rol" class="form-label" style="color: #000;">Roles</label>
                                                        <input type="hidden" name="id_usuario" id="id_usuario" value="' . htmlspecialchars($usuario->id) . '">
                                                        <select name="q_id_rol" id="q_id_rol" class="form-select">
                                                            <option value="">Seleccione rol</option>';
                                                            
                                                            $consulta = "call si_rol($usuario->id)";
                                                            $roles = $conexion->seleccionar($consulta);
                                                            
                                                            foreach ($roles as $rol) {
                                                                echo '<option value="' . htmlspecialchars($rol->id_rol) . '">' . htmlspecialchars($rol->rol) . '</option>';
                                                            }
                                                        echo '
                                                        </select>
                                                    </div>
                                                    <button type="submit" name="quitar_rol" class="btn btn-success">Quitar rol</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            '; 

                            echo 
                                '
                                <div class="modal fade" id="reg-' . htmlspecialchars($usuario->id) . '" tabindex="-1" aria-labelledby="reg-' . htmlspecialchars($usuario->id) . 'Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 style="color: #000;" class="modal-title fs-5" id="reg-' . htmlspecialchars($usuario->id) . 'Label">Registrar como empleado</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form" method="POST">
                                                    <input type="hidden" name="id_usuario" id="id_usuario" value="' . htmlspecialchars($usuario->id) . '">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label" style="color: #000;">RFC</label>
                                                        <input type="text" class="form-control" name="rfc" id="rfc" maxlength="13">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label" style="color: #000;">NSS</label>
                                                        <input type="text" class="form-control" name="nss" id="nss" maxlength="10">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label" style="color: #000;">CURP</label>
                                                        <input type="text" class="form-control" required name="curp" id="curp" maxlength="18">
                                                    </div>
                                                    <button type="submit" name="registrar_empleado" class="btn btn-success">Registrar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            '; 

                            echo 
                                '
                                <div class="modal fade" id="dis-' . htmlspecialchars($usuario->id) . '" tabindex="-1" aria-labelledby="dis-' . htmlspecialchars($usuario->id) . 'Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 style="color: #000;" class="modal-title fs-5" id="dis-' . htmlspecialchars($usuario->id) . 'Label">Asignar dispositivo</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="form" method="POST">
                                                    <div class="mb-3">
                                                        <label style="color: #000;" for="" class="form-label">Usuario</label>
                                                        <input type="hidden" name="id_usuario" id="id_usuario" value="' . htmlspecialchars($usuario->id) . '">
                                                        <input type="text" class="form-control" value="' . htmlspecialchars($usuario->usuario) . '" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label style="color: #000;" for="" class="form-label">Marca</label>
                                                        <select name="marca" id="marca" class="form-select" required>
                                                            <option value="">Seleccione marca</option>
                                                        ';
                                                        $consulta = "select marcas.id_marca, marcas.marca from marcas";
                                                        $marcas = $conexion->seleccionar($consulta);

                                                        foreach ($marcas as $marca)
                                                        {
                                                            echo '<option value="' . htmlspecialchars($marca->id_marca) . '">' . htmlspecialchars($marca->marca) . '</option>';
                                                        }
                                                echo '
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label style="color: #000;" for="" class="form-label">Dispositivo</label>
                                                        <input  name="dispositivo" id="dispositivo" type="text" class="form-control" required maxlength="128">
                                                    </div>
                                                    <button type="submit" name="asignar_dispositivo" class="btn btn-success">Asignar dispositivo</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            '; 
                            }
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['a_id_rol']))
                            {
                                $id_usuario = htmlspecialchars($_POST['id_usuario']);
                                $id_rol = htmlspecialchars($_POST['a_id_rol']);

                                $consulta = "call asignar_rol($id_usuario, $id_rol)";
                                $conexion->seleccionar($consulta);
                            } 
                            elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quitar_rol'])) 
                            {
                                $id_usuario = htmlspecialchars($_POST['id_usuario']);
                                $id_rol = htmlspecialchars($_POST['q_id_rol']);

                                $consulta = "call quitar_rol($id_usuario, $id_rol)";
                                $conexion->seleccionar($consulta);
                            }
                            elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_empleado']))
                            {
                                $id_usuario = htmlspecialchars($_POST['id_usuario']);
                                $rfc = htmlspecialchars($_POST['rfc']);
                                $nss = htmlspecialchars($_POST['nss']);
                                $curp = htmlspecialchars($_POST['curp']);

                                $consulta = "call registrar_empleado ($id_usuario, '$rfc', '$nss', '$curp')";
                                $conexion->seleccionar($consulta);
                            }
                            elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignar_dispositivo']))
                            {
                                $id_usuario = htmlspecialchars($_POST['id_usuario']);
                                $marca = htmlspecialchars($_POST['marca']);
                                $dispositivo = htmlspecialchars($_POST['dispositivo']);

                                $consulta = "call asignar_dispositivo ($id_usuario, $marca, '$dispositivo')";
                                $conexion->seleccionar($consulta);
                            }
                        
                            ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const showAlert = (title, text, icon) => {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = './admin_gestion_usuario.php';
                });
            };

            if (<?php echo isset($_POST['asignar_rol']) ? 'true' : 'false'; ?>) {
                showAlert('Rol Asignado', 'El rol se ha asignado correctamente.', 'success');
            }
            if (<?php echo isset($_POST['quitar_rol']) ? 'true' : 'false'; ?>) {
                showAlert('Rol Quitado', 'El rol se ha quitado correctamente.', 'success');
            }
            if (<?php echo isset($_POST['registrar_empleado']) ? 'true' : 'false'; ?>) {
                showAlert('Empleado Registrado', 'El empleado se ha registrado correctamente.', 'success');
            }
            if (<?php echo isset($_POST['asignar_dispositivo']) ? 'true' : 'false'; ?>) {
                showAlert('Dispositivo Asignado', 'El dispositivo se ha asignado correctamente.', 'success');
            }
        });
    </script>


    <script>
        document.getElementById('search-box').addEventListener('input', function() 
        {
            let input = this.value;
            if (input.length < 3) 
            {
                document.getElementById('autocomplete-results').innerHTML = '';
                return;
            }

            fetch('../scripts/autocompletar.php?query=' + input)
                .then(response => response.json())
                .then(data => {
                    let autocompleteResults = document.getElementById('autocomplete-results');
                    autocompleteResults.innerHTML = '';
                    data.forEach(user => {
                        let item = document.createElement('div');
                        item.textContent = user.nombre_usuario;
                        item.addEventListener('click', function() {
                            document.getElementById('search-box').value = user.nombre_usuario;
                            document.getElementById('autocomplete-results').innerHTML = '';
                            showDeviceAndServiceDropdowns(user.nombre_usuario);
                        });
                        autocompleteResults.appendChild(item);
                    });
                });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>