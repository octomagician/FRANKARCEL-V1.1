<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$message = '';
$alertType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        $tipo_servicio = $_POST['tipo_servicio'];
        $servicio = $_POST['servicio'];
        $descripcion = $_POST['descripcion'];

        $consulta = "call nuevo_servicio('$tipo_servicio', '$servicio', '$descripcion');";
        $conexion->seleccionar($consulta);
        $message = 'Servicio agregado exitosamente!';
        $alertType = 'success';
    } elseif (isset($_POST['update_service'])) {
        $nombre_servicio = $_POST['servicio_modificar'];
        $nuevo_nombre = $_POST['servicio'];
        $descripcion = $_POST['descripcion'];

        $consulta = "call editar_servicio('$nombre_servicio', '$nuevo_nombre', '$descripcion');";
        $conexion->seleccionar($consulta);
        $message = 'Servicio actualizado exitosamente!';
        $alertType = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .table_wrapper{
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

        toggleSidebarBtn.addEventListener('click', () => 
        {
            sidebar.classList.toggle('sidebar-open');
        });

        hideSidebarBtn.addEventListener('click', () => 
        {
            sidebar.classList.remove('sidebar-open');
        });
    </script>

    <div class="main-content" style="margin-top: 35px;">
        <h2>Gestión de servicios</h2>
            <div class="row mb-3">
                <div class="col-sm-12 col-lg-3">
                    <form action="#" method="POST">
                        <?php
                            $consulta = "select * from tipo_servicio;";
                            $reg = $conexion->seleccionar($consulta);
                            
                            echo '
                                <label for="tipo_servicio" class="form-label">Tipo servicio</label>
                                <select name="tipo_servicio" id="tipo_servicio" class="form-select">
                                <option value="">Seleccionar tipo de servicio</option>
                            ';

                            foreach ($reg as $value) {
                                echo "<option value='{$value->tipo_servicio}'>{$value->tipo_servicio}</option>";
                            }

                            echo '
                                </select>
                            ';
                        ?>
                        <input type="submit" class="form-control btn btn-success mt-3" value="Ver">
                    </form>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-sm-12 col-lg-3 d-flex align-items-center justify-content-center justify-content-md-end mt-3">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Agregar Servicio</button>
                </div>
            </div>

        <div class="table_wrapper">
            <table class="table table-hover mt-3">
                <thead class="table-success">
                    <tr>
                        <th>Servicio</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo_servicio'])) {
                        $tipo_servicio = $_POST['tipo_servicio'];
                        $consulta = "call ver_servicios('$tipo_servicio')";
                        $servicios = $conexion->seleccionar($consulta);

                        if ($servicios) {
                            foreach ($servicios as $servicio) {
                                echo "<tr>";
                                echo "<td>" . (isset($servicio->servicio) ? $servicio->servicio : 'N/A') . "</td>";
                                echo "<td>" . (isset($servicio->descripcion) ? $servicio->descripcion : 'N/A') . "</td>";
                                echo "<td><button class='btn btn-success' data-bs-toggle='modal' data-bs-target='#editModal' data-bs-servicio='" . (isset($servicio->servicio) ? $servicio->servicio : '') . "' data-bs-descripcion='" . (isset($servicio->descripcion) ? $servicio->descripcion : '') . "'>Editar</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No se encontraron servicios para este tipo.</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="container mt-5">
            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Servicio</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="servicio_modificar" class="form-label">Servicio a modificar</label>
                                    <input type="text" class="form-control" id="servicio_modificar" name="servicio_modificar" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="servicio" class="form-label">Servicio</label>
                                    <input type="text" class="form-control" id="servicio" name="servicio" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="form-control btn btn-success" name="update_service" value="Guardar cambios">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para agregar servicio -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel" style="color: #000;">Agregar Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                            $consulta = "select * from tipo_servicio;";
                            $reg = $conexion->seleccionar($consulta);
                            
                            echo '
                                <label for="tipo_servicio_add" class="form-label" style="color: #000;">Tipo servicio</label>
                                <select name="tipo_servicio" id="tipo_servicio_add" class="form-select" required>
                                <option value="">Seleccionar tipo de servicio</option>
                            ';

                            foreach ($reg as $value) 
                            {
                                echo "<option value='{$value->tipo_servicio}'>{$value->tipo_servicio}</option>";
                            }

                            echo '
                                </select>
                            ';
                        ?>
                        <div class="mb-3">
                            <label for="servicio_add" class="form-label" style="color: #000;">Servicio</label>
                            <input type="text" class="form-control" id="servicio_add" name="servicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion_add" class="form-label" style="color: #000;">Descripción</label>
                            <textarea class="form-control" id="descripcion_add" name="descripcion" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" name="add_service" id="submit_button">Agregar Servicio</button>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() 
                    {
                        const tipoServicioSelect = document.getElementById('tipo_servicio_add');
                        const submitButton = document.getElementById('submit_button');

                        tipoServicioSelect.addEventListener('change', function() 
                        {
                            if (tipoServicioSelect.value === '') 
                            {
                                submitButton.disabled = true;
                                submitButton.title = 'Por favor, seleccione un tipo de servicio.';
                            } 
                            else 
                            {
                                submitButton.disabled = false;
                                submitButton.title = '';
                            }
                        });

                        if (tipoServicioSelect.value === '') 
                        {
                            submitButton.disabled = true;
                            submitButton.title = 'Por favor, seleccione un tipo de servicio.';
                        }
                    });
                </script>

                </div>
            </div>
        </div>
    </div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() 
{
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) 
    {
        var button = event.relatedTarget;
        var servicio = button.getAttribute('data-bs-servicio');
        var descripcion = button.getAttribute('data-bs-descripcion');
        
        var modalServicioModificar = editModal.querySelector('#servicio_modificar');
        var modalServicio = editModal.querySelector('#servicio');
        var modalDescripcion = editModal.querySelector('#descripcion');
        
        modalServicioModificar.value = servicio;
        modalServicio.value = servicio;
        modalDescripcion.value = descripcion;
    });
});
</script>

    <?php if ($message && $alertType): ?>
    <script>
        Swal.fire({
            icon: '<?php echo $alertType; ?>',
            title: '<?php echo $message; ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
