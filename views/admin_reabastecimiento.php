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
    <title>Document</title>
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

    <div class="main-content" style="margin-top: 35px;">
        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <h2>Reabastecimiento</h2>
                <div style="width: 100%;">
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
                        <form action="" method="POST">
                            <label for="productos_reabastecimiento" class="form-label">Productos</label>
                            <div class="d-flex flex-column">
                                <select name="productos_reabastecimiento" id="productos_reabastecimiento" class="form-select">
                                    <option value="">Seleccionar producto</option>
                                    <?php
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoria_reabastecimiento'])) {
                                            $categoria_reabastecimiento = $_POST['categoria_reabastecimiento'];
                                            
                                            $productos = $conexion->seleccionar("CALL productos_categoria(:categoria)", ['categoria' => $categoria_reabastecimiento]);

                                            $opciones = $conexion->seleccionar("CALL opciones_producto(:categoria)", ['categoria' => $categoria_reabastecimiento]);
                                            $opciones = !empty($opciones) ? $opciones[0] : null;

                                            if ($opciones) 
                                            {
                                                foreach ($productos as $producto) 
                                                {
                                                    $selected = (isset($_POST['productos_reabastecimiento']) && $_POST['productos_reabastecimiento'] == $producto->id_producto) ? 'selected' : '';
                                                    echo "<option value='" . htmlspecialchars($producto->id_producto, ENT_QUOTES, 'UTF-8') . "' $selected>";
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
                                    <label for="inversion" class="form-label">Inversión</label>
                                    <input type="number" name="inversion" id="inversion" class="form-control" min="0" required value="<?php echo isset($_POST['inversion']) ? $_POST['inversion'] : ''; ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad_reabastecida" class="form-label">Cantidad reabastecida</label>
                                <div class="d-flex">
                                    <input type="number" name="cantidad_reabastecida" id="cantidad_reabastecida" class="form-control" min="1" step="1" required value="<?php echo isset($_POST['cantidad_reabastecida']) ? $_POST['cantidad_reabastecida'] : ''; ?>">
                                    <input type="submit" id="btn-reabastecer" class="btn btn-success ml-auto btn-adjust" value="Reabastecer" style="margin-left: 10px;" disabled>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const selectProducto = document.getElementById('productos_reabastecimiento');
                                        const btnReabastecer = document.getElementById('btn-reabastecer');

                                        function toggleButton() {
                                            if (selectProducto.value) {
                                                btnReabastecer.disabled = false;
                                            } else {
                                                btnReabastecer.disabled = true;
                                            }
                                        }

                                        toggleButton();

                                        selectProducto.addEventListener('change', toggleButton);
                                    });
                                </script>

                            </div>
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cantidad_reabastecida']) && isset($_POST['productos_reabastecimiento']) && isset($_POST['inversion'])) 
                            {
                                $productos_reabastecimiento = $_POST['productos_reabastecimiento'];
                                $cantidad_reabastecida = $_POST['cantidad_reabastecida'];
                                $inversion = $_POST['inversion'];

                                $consulta = "CALL reabastecer_producto(:producto, :cantidad, :inversion)";
                                $params = 
                                [
                                    'producto' => $productos_reabastecimiento,
                                    'cantidad' => $cantidad_reabastecida,
                                    'inversion' => $inversion
                                ];

                                try 
                                {
                                    $conexion->ejecutarProcedimiento('reabastecer_producto', $params);
                                    echo "<script>
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Éxito',
                                                text: 'Reabastecimiento realizado con éxito',
                                                confirmButtonText: 'Aceptar'
                                            });
                                        </script>";
                                } 
                                catch (Exception $e) 
                                {
                                    echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Error al realizar el reabastecimiento: " . addslashes($e->getMessage()) . "',
                                                confirmButtonText: 'Aceptar'
                                            });
                                        </script>";
                                }
                            }
                        ?>

                    </div>
                    <div class="mb-3">
                        <h2>Filtros para historial</h2>
                        <div class="mb-3">
                            <form action="" class="form" method="POST">
                                <select name="orden_historial" id="orden_historial" class="form-select mb-3">
                                    <option value="">Seleccionar orden de fechas</option>
                                    <option value="mayor-menor">Mayor a menor</option>
                                    <option value="menor-mayor">Menor a mayor</option>
                                </select>
                                <input type="submit" class="btn btn-success" value="Ver historial" id="historial" name="historial">
                            </form>
                        </div>
                        <div class="mb-3">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="filtro_fechas" class="form-label"><strong>Filtrar por rango de fechas</strong></label>
                                    <div style="display: flex; gap: 10px;">
                                        <input type="date" id="filtro_fecha_inicio" name="fecha1" class="form-control" required>
                                        <input type="date" id="filtro_fecha_fin" name="fecha2" class="form-control" required>
                                    </div>
                                </div>
                                <button type="submit" name="historial" class="btn btn-success"><strong>Filtrar por fecha</strong></button>
                            </form>
                        </div>

                        <script>
                            const fechaInicio = document.getElementById('filtro_fecha_inicio');
                            const fechaFin = document.getElementById('filtro_fecha_fin');
                            const hoy = new Date().toISOString().split('T')[0];

                            fechaInicio.setAttribute('max', hoy);
                            fechaFin.setAttribute('max', hoy);

                            fechaInicio.addEventListener('change', () => {
                                fechaFin.setAttribute('min', fechaInicio.value);
                            });

                            fechaFin.addEventListener('change', () => {
                                fechaInicio.setAttribute('max', fechaFin.value);
                            });
                        </script>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="filtro_categorias" class="form-label">Filtrar por categoria</label>
                                <select name="filtro_categoria" id="filtro_categoria" class="form-select mb-3">
                                    <option value="">Elegir categoria</option>
                                    <?php
                                        $consulta = "select categoria_producto.categoria as cat from categoria_producto;";
                                        $categorias = $conexion->seleccionar($consulta);

                                        foreach ($categorias as $categoria) {
                                            echo "<option value='{$categoria->cat}'>{$categoria->cat}</option>";
                                        }
                                    ?>
                                </select>
                                <button type="submit" name="historial" class="btn btn-success">Filtrar por categoria</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-sm-12">
                <h2>Historial de Reabastecimiento</h2>
                <div class="table_wrapper">
                    <table class="table table-hover">
                        <thead class="table-success">
                            <tr>
                                <th>ID Producto</th>
                                <th>Categoría</th>
                                <th>Cantidad reabastecida</th>
                                <th>Stock final</th>
                                <th>Inversión</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (isset($_POST['historial']) && !empty($_POST['orden_historial'])) 
                                {
                                    $orden_historial = $_POST['orden_historial'];
                                
                                    if ($orden_historial == "mayor-menor") 
                                    {
                                        $consulta = "select * from historial_reabastecimiento_desc";
                                    } 
                                    elseif ($orden_historial == "menor-mayor") 
                                    {
                                        $consulta = "select * from historial_reabastecimiento_asc";
                                    } 
                                    else 
                                    {
                                        $consulta = "";
                                        echo '<td>Seleccione opción correcta</td>';
                                    }
                                
                                    if ($consulta) 
                                    {
                                        $datos = $conexion->seleccionar($consulta);
                                
                                        $sum_inv = 0;

                                        foreach ($datos as $dato) 
                                        {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($dato->id_producto) . '</td>';
                                            echo '<td>' . htmlspecialchars($dato->categoria) . '</td>';
                                            echo '<td>' . htmlspecialchars($dato->cantidad_reabastecida) . '</td>';
                                            echo '<td>' . htmlspecialchars($dato->stock_final) . '</td>';
                                            echo '<td>$' . htmlspecialchars($dato->inversion) . '</td>';
                                            echo '<td>' . htmlspecialchars($dato->fecha) . '</td>';
                                            echo '</tr>';

                                            $sum_inv += $dato->inversion;
                                        }
                                        echo '<tr>';
                                        echo '<td colspan="4"></td>';
                                        echo '<td><strong>Total Inversion:</strong> $' . htmlspecialchars($sum_inv) . '</td>';
                                        echo '<td></td>';
                                        echo '</tr>';
                                    }
                                }
                                elseif (isset($_POST['historial']) && !empty($_POST['fecha1']) && !empty($_POST['fecha2'])) 
                                {
                                    $fecha1 = $_POST['fecha1'];
                                    $fecha2 = $_POST['fecha2'];

                                    $consulta = "call filtro_historial_fechas('$fecha1', '$fecha2')";
                                
                                    $datos = $conexion->seleccionar($consulta);

                                    $sum_inv = 0;
                                
                                    foreach ($datos as $dato) 
                                    {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($dato->id_producto) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->categoria) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->cantidad_reabastecida) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->stock_final) . '</td>';
                                        echo '<td>$' . htmlspecialchars($dato->inversion) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->fecha) . '</td>';
                                        echo '</tr>';

                                        $sum_inv += $dato->inversion; 
                                    }

                                    echo '<tr>';
                                    echo '<td colspan="4"></td>';
                                    echo '<td><strong>Total Inversion:</strong> $' . htmlspecialchars($sum_inv) . '</td>';
                                    echo '<td></td>';
                                    echo '</tr>';
                                }
                                elseif (isset($_POST['historial']) && isset($_POST['filtro_categoria']) && !empty($_POST['filtro_categoria'])) 
                                {
                                    $categoria = $_POST['filtro_categoria'];

                                    $consulta = "call filtro_categoria('$categoria')";

                                    $datos = $conexion->seleccionar($consulta);

                                    $sum_inv = 0;

                                    foreach ($datos as $dato) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($dato->id_producto) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->categoria) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->cantidad_reabastecida) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->stock_final) . '</td>';
                                        echo '<td>$' . htmlspecialchars($dato->inversion) . '</td>';
                                        echo '<td>' . htmlspecialchars($dato->fecha) . '</td>';
                                        echo '</tr>';

                                        $sum_inv += $dato->inversion; 
                                    }

                                    echo '<tr>';
                                    echo '<td colspan="4"></td>';
                                    echo '<td><strong>Total Inversion:</strong> $' . htmlspecialchars($sum_inv) . '</td>';
                                    echo '<td></td>';
                                    echo '</tr>';
                                }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>