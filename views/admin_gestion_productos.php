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
    <title>Gestion de productos</title>
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
            width: 200px;
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

    <?php
        $error_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if (isset($_POST['filtrar_categoria'])) 
            {
                $filtro_categoria = $_POST['filtro_categoria'];

                if ($filtro_categoria == '') 
                {
                    $error_message = 'Debe seleccionar una categoría.';
                } 
                else 
                {
                    $consulta = "call productos_categoria('$filtro_categoria')";
                    $productos = $conexion->seleccionar($consulta);
                }

            } 
            elseif (isset($_POST['filtrar_precio'])) 
            {
                $filtro_precio = $_POST['filtro_precio'];

                if ($filtro_precio == '') 
                {
                    $error_message = 'Debe seleccionar un filtro de precio.';
                } 
                else 
                {
                    if ($filtro_precio == 'p_mayor-menor') {
                        $consulta = "select * from admin_precio_mayor_menor";
                    } elseif ($filtro_precio == 'p_menor-mayor') {
                        $consulta = "select * from admin_precio_menor_mayor";
                    }
                    $productos = $conexion->seleccionar($consulta);
                }

            } 
            elseif (isset($_POST['filtrar_stock'])) 
            {
                $filtro_stock = $_POST['filtro_stock'];

                if ($filtro_stock == '') 
                {
                    $error_message = 'Debe seleccionar un filtro de stock.';
                } 
                else 
                {
                    if ($filtro_stock == 's_mayor-menor') 
                    {
                        $consulta = "select * from stock_mayor_menor";
                    } 
                    elseif ($filtro_stock == 's_menor-mayor') 
                    {
                        $consulta = "select * from stock_menor_mayor";
                    }
                    $productos = $conexion->seleccionar($consulta);
                }
            }
        }
    ?>

    <!--

        Codigo para lo que se muetstra (BOTONES Y TABLA)

    -->

    <div class="main-content" style="margin-top: 35px;">
        <h2>Gestión de productos y control de inventario</h2>
        <div class="mb-3">
            <div class="mb-3">
                <div class="row">
                    <div class="col-sm-12 col-lg-2 mt-3">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_producto_modal" style="width: 150px;">
                            Agregar producto
                        </button>
                    </div>
                    <div class="col-sm-12 col-lg-2 mt-3">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_categoria_modal" style="width: 150px;">
                            Agregar categoría
                        </button>
                    </div>
                    <div class="col-sm-12 col-lg-2 mt-3">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_marca_modal" style="width: 150px;">
                            Agregar marca
                        </button>
                    </div>
                    <div class="col-lg-9 mt-3"></div>
                </div>
            </div>

            <form method="POST" action="" class="d-flex align-items-center form-inline">
                <div class="row">
                    <div class="col-sm-12 col-lg-4">
                        <div class="me-2">
                            <input type="search" id="buscar_producto_id" name="buscar_producto_id" class="form-control" placeholder="Insertar ID">
                        </div>
                        <input type="submit" name="buscar_id" class="btn btn-success me-2 mb-3 mt-3" value="Buscar ID">
                    </div>

                    <div class="col-sm-12 col-lg-4">
                        <div class="me-2">
                            <input type="search" id="buscar_producto" name="buscar_producto" class="form-control" placeholder="Insertar producto">
                        </div>
                        <input type="submit" name="buscar_producto_submit" class="btn btn-success me-2 mb-3 mt-3" value="Buscar producto">
                    </div>

                    <div class="col-sm-12 col-lg-4">
                        <div class="me-2">
                            <select name="filtro_categoria" id="buscar_categoria" class="form-select">
                                <option value="">Filtrar por categoría</option>
                                <?php
                                    $consulta = "SELECT categoria_producto.categoria AS cat FROM categoria_producto;";
                                    $categorias = $conexion->seleccionar($consulta);

                                    foreach ($categorias as $categoria) 
                                    {
                                        echo "<option value='{$categoria->cat}'>{$categoria->cat}</option>";
                                    }
                                ?>
                            </select>
                            <input type="submit" name="filtrar_categoria" class="btn btn-success me-2 mb-3 mt-3" value="Filtrar por categoría">
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-4">
                        <div class="me-2">
                            <select name="filtro_precio" id="filtro_precio" class="form-select">
                                <option value="">Filtrar por precio</option>
                                <option value="p_mayor-menor">Mayor a menor</option>
                                <option value="p_menor-mayor">Menor a mayor</option>
                            </select>
                        </div>
                        <input type="submit" name="filtrar_precio" class="btn btn-success me-2 mb-3 mt-3" value="Filtrar por precio">
                    </div>

                    <div class="col-sm-12 col-lg-4">
                        <div class="me-2">
                            <select name="filtro_stock" id="filtro_stock" class="form-select">
                                <option value="">Filtrar por stock</option>
                                <option value="s_mayor-menor">Mayor a menor</option>
                                <option value="s_menor-mayor">Menor a mayor</option>
                            </select>
                        </div>
                        <input type="submit" name="filtrar_stock" class="btn btn-success mb-3 mt-3" value="Filtrar por stock">
                    </div>
                </div>
            </form>

    <div class="mb-3 mt-3">
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') 
            {
                $productos = [];
                $thead = [
                    'color' => true,
                    'tamano' => true,
                    'ram' => true,
                    'almacenamiento' => true,
                    'material' => true,
                    'compatibilidad' => true,
                ];

                try {
                    if (isset($_POST['buscar_producto_id']) && !empty($_POST['buscar_producto_id'])) 
                    {
                        $id_producto = intval($_POST['buscar_producto_id']);
                        $consulta = "CALL buscar_producto_id($id_producto)";
                        $productos = $conexion->seleccionar($consulta);
                    } 
                    elseif (isset($_POST['buscar_producto']) && !empty($_POST['buscar_producto'])) 
                    {
                        $producto = $_POST['buscar_producto'];
                        $consulta = "CALL buscar_producto('$producto')";
                        $productos = $conexion->seleccionar($consulta);
                    } 
                    elseif (isset($_POST['filtrar_categoria']) && !empty($_POST['filtro_categoria'])) 
                    {
                        $filtro_categoria = $_POST['filtro_categoria'];
                        $consulta = "CALL productos_categoria('$filtro_categoria')";
                        $productos = $conexion->seleccionar($consulta);

                        $consultaOpciones = "CALL opciones_producto('$filtro_categoria')";
                        $resultadoOpciones = $conexion->seleccionar($consultaOpciones);

                        if ($resultadoOpciones) 
                        {
                            $opciones = $resultadoOpciones[0];

                            $thead['ram'] = isset($opciones->ram) && $opciones->ram == 1;
                            $thead['almacenamiento'] = isset($opciones->almacenamiento) && $opciones->almacenamiento == 1;
                            $thead['color'] = isset($opciones->color) && $opciones->color == 1;
                            $thead['tamano'] = isset($opciones->tamano) && $opciones->tamano == 1;
                            $thead['material'] = isset($opciones->material) && $opciones->material == 1;
                            $thead['compatibilidad'] = isset($opciones->compatibilidad) && $opciones->compatibilidad == 1;
                        } 
                        else 
                        {
                            echo '<div class="alert alert-danger">No se encontraron opciones para la categoría seleccionada.</div>';
                        }
                    } 
                    elseif (isset($_POST['filtrar_precio']) && !empty($_POST['filtro_precio'])) 
                    {
                        $filtro_precio = $_POST['filtro_precio'];

                        if ($filtro_precio == 'p_mayor-menor') 
                        {
                            $consulta = "select * from precio_mayor_menor";
                        } 
                        elseif ($filtro_precio == 'p_menor-mayor') 
                        {
                            $consulta = "select * from precio_menor_mayor";
                        }

                        $productos = $conexion->seleccionar($consulta);

                    } 
                    elseif (isset($_POST['filtrar_stock']) && !empty($_POST['filtro_stock'])) 
                    {
                        $filtro_stock = $_POST['filtro_stock'];

                        if ($filtro_stock == 's_mayor-menor') 
                        {
                            $consulta = "select * from stock_mayor_menor";
                        } 
                        elseif ($filtro_stock == 's_menor-mayor') 
                        {
                            $consulta = "select * from stock_menor_mayor";
                        }

                        $productos = $conexion->seleccionar($consulta);

                    }

                    if ($productos) 
                    {
                        echo '<div class="table_wrapper">';
                        echo '<table class="table table-hover">';
                        echo '<thead class="table-success">';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Producto</th>';
                        echo '<th>Marca</th>';
                        if ($thead['color']) echo '<th id="color-header">Color</th>';
                        if ($thead['tamano']) echo '<th id="tamano-header">Tamaño</th>';
                        if ($thead['ram']) echo '<th id="ram-header">Memoria Ram</th>';
                        if ($thead['almacenamiento']) echo '<th id="almacenamiento-header">Almacenamiento</th>';
                        if ($thead['material']) echo '<th id="material-header">Material</th>';
                        if ($thead['compatibilidad']) echo '<th id="compatibilidad-header">Compatibilidad</th>';
                        echo '<th>Precio</th>';
                        echo '<th>Stock</th>';
                        echo '<th>Categoría</th>';
                        echo '<th>Acciones</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        foreach ($productos as $producto) 
                        {
                            echo '<tr>';
                            echo '<td id="id_producto_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '</td>';
                            echo '<td id="nombre_producto_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->nombre_producto) ? htmlspecialchars($producto->nombre_producto) : '') . '</td>';
                            echo '<td id="marca_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->marca) ? htmlspecialchars($producto->marca) : '') . '</td>';
                            if ($thead['color']) echo '<td id="color_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->color) ? htmlspecialchars($producto->color) : '') . '</td>';
                            if ($thead['tamano']) echo '<td id="tamano_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->tamano) ? htmlspecialchars($producto->tamano) : '') . '</td>';
                            if ($thead['ram']) echo '<td id="ram_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->ram) ? htmlspecialchars($producto->ram) : '') . '</td>';
                            if ($thead['almacenamiento']) echo '<td id="almacenamiento_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->almacenamiento) ? htmlspecialchars($producto->almacenamiento) : '') . '</td>';
                            if ($thead['material']) echo '<td id="material_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->material) ? htmlspecialchars($producto->material) : '') . '</td>';
                            if ($thead['compatibilidad']) echo '<td id="compatibilidad_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->compatibilidad) ? htmlspecialchars($producto->compatibilidad) : '') . '</td>';
                            echo '<td id="precio_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->precio) ? htmlspecialchars($producto->precio) : '') . '</td>';
                            echo '<td id="stock_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->stock) ? htmlspecialchars($producto->stock) : '') . '</td>';
                            echo '<td id="categoria_' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">' . (isset($producto->categoria) ? htmlspecialchars($producto->categoria) : '') . '</td>';
                            echo '<td><button type="button" class="btn btn-success edit-btn" data-bs-toggle="modal" data-bs-target="#acciones_modal" data-id="' . (isset($producto->id_producto) ? htmlspecialchars($producto->id_producto) : '') . '">Editar</button></td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';

                    } 
                    else 
                    {
                        echo '<p>No hay productos disponibles.</p>';
                    }
                } 
                catch (Exception $e) 
                {
                    echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }
        ?>
    </div>

    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            const filtroCategoria = urlParams.get('filtro_categoria');
            if (filtroCategoria) 
            {
                <?php foreach ($thead as $header => $show) { ?>
                    document.getElementById('<?php echo $header; ?>-header').style.display = '<?php echo $show ? 'table-cell' : 'none'; ?>';
                <?php } ?>
            } 
            else 
            {
                document.querySelectorAll('th').forEach(th => th.style.display = 'table-cell');
            }
        });
    </script>

    <!--

        Modal para la edicion de un producto

    -->
    
    <div class="modal fade" id="acciones_modal" tabindex="-1" aria-labelledby="acciones_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="acciones_modal_label" style="color: #000;">Editar producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editar_producto_form" class="form">
                        <div class="mb-3">
                            <label for="editar_id_producto" class="form-label" style="color: #000;">ID Producto</label>
                            <input type="text" class="form-control" id="editar_id_producto" name="editar_id_producto" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editar_nombre_producto" class="form-label" style="color: #000;">Producto</label>
                            <input type="text" class="form-control" id="editar_nombre_producto" name="editar_nombre_producto">
                        </div>
                        <div class="mb-3">
                            <label for="editar_marca" class="form-label" style="color: #000;">Marca</label>
                            <select name="editar_marca" id="editar_marca" class="form-select" required>
                                <option value="">Seleccionar marca</option>
                                <?php
                                    $consulta = "select marcas.marca from marcas;";
                                    $conexion = new database();
                                    $marcas = $conexion->seleccionar($consulta);
                                    foreach ($marcas as $marca) 
                                    {
                                        echo "<option value='{$marca->marca}'>{$marca->marca}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editar_categoria" class="form-label" style="color: #000;">Categoria</label>
                            <select name="editar_categoria" id="editar_categoria" class="form-select">
                                <option value="">Editar Categoria</option>
                                <?php
                                    $consulta = "select categoria_producto.categoria as cat from categoria_producto;";
                                    $categorias = $conexion->seleccionar($consulta);
                                    foreach ($categorias as $categoria) 
                                    {
                                        echo "<option value='{$categoria->cat}'>{$categoria->cat}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3" id="campos_adicionales"></div>
                        <div class="mb-3">
                            <label for="editar_precio" class="form-label" style="color: #000;">Precio</label>
                            <input type="number" min="0" class="form-control" id="editar_precio" name="editar_precio">
                        </div>
                        <div class="mb-3">
                            <label for="editar_descripcion_breve" class="form-label" style="color: #000;">Descripción Breve</label>
                            <textarea class="form-control" id="editar_descripcion_breve" name="editar_descripcion_breve" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editar_descripcion_larga" class="form-label" style="color: #000;">Descripción Larga</label>
                            <textarea class="form-control" id="editar_descripcion_larga" name="editar_descripcion_larga" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const idProducto = this.getAttribute('data-id');
                    
                    const nombreProducto = document.getElementById('nombre_producto_' + idProducto).innerText;
                    const marca = document.getElementById('marca_' + idProducto).innerText;
                    const categoria = document.getElementById('categoria_' + idProducto).innerText;
                    const precio = document.getElementById('precio_' + idProducto).innerText;

                    document.getElementById('editar_id_producto').value = idProducto;
                    document.getElementById('editar_nombre_producto').value = nombreProducto;
                    document.getElementById('editar_marca').value = marca;
                    document.getElementById('editar_categoria').value = categoria;
                    document.getElementById('editar_precio').value = precio;

                    const camposAdicionales = document.getElementById('campos_adicionales');
                    camposAdicionales.innerHTML = '';

                    fetch('../scripts/opciones_producto.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'categoria=' + encodeURIComponent(categoria)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.ram == 1) {
                            const ram = document.getElementById('ram_' + idProducto).innerText;
                            camposAdicionales.innerHTML += `
                                <div class="mb-3">
                                    <label for="editar_ram" class="form-label">Memoria Ram</label>
                                    <input type="number" min="0" step="0.01" class="form-control" id="editar_ram" name="editar_ram" value="${ram}" required>
                                </div>
                            `;
                        }

                        if (data.almacenamiento == 1) {
                            const almacenamiento = document.getElementById('almacenamiento_' + idProducto).innerText;
                            camposAdicionales.innerHTML += `
                                <div class="mb-3">
                                    <label for="editar_almacenamiento" class="form-label">Almacenamiento</label>
                                    <input type="number" min="0" step="0.01" class="form-control" id="editar_almacenamiento" name="editar_almacenamiento" value="${almacenamiento}" required>
                                </div>
                            `;
                        }

                        if (data.color == 1) {
                            const color = document.getElementById('color_' + idProducto).innerText;
                            camposAdicionales.innerHTML += `
                                <div class="mb-3">
                                    <label for="editar_color" class="form-label">Color</label>
                                    <input type="text" class="form-control" id="editar_color" name="editar_color" value="${color}" required>
                                </div>
                            `;
                        }

                        if (data.tamano == 1) {
                            const tamano = document.getElementById('tamano_' + idProducto).innerText;
                            camposAdicionales.innerHTML += `
                                <div class="mb-3">
                                    <label for="editar_tamano" class="form-label">Tamaño</label>
                                    <input type="number" min="0" step="0.01" class="form-control" id="editar_tamano" name="editar_tamano" value="${tamano}" required>
                                </div>
                            `;
                        }

                        if (data.material == 1) {
                            const material = document.getElementById('material_' + idProducto).innerText;
                            camposAdicionales.innerHTML += `
                                <div class="mb-3">
                                    <label for="editar_material" class="form-label">Material</label>
                                    <input type="text" class="form-control" id="editar_material" name="editar_material" value="${material}" required>
                                </div>
                            `;
                        }

                        if (data.compatibilidad == 1) {
                            const compatibilidad = document.getElementById('compatibilidad_' + idProducto).innerText;
                            camposAdicionales.innerHTML += `
                                <div class="mb-3">
                                    <label for="editar_compatibilidad" class="form-label">Compatibilidad</label>
                                    <input type="text" class="form-control" id="editar_compatibilidad" name="editar_compatibilidad" value="${compatibilidad}" required>
                                </div>
                            `;
                        }
                    });

                    fetch('../scripts/buscar_descripciones.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id_producto=' + encodeURIComponent(idProducto)
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('editar_descripcion_breve').value = data.descripcion_breve;
                        document.getElementById('editar_descripcion_larga').value = data.descripcion_larga;
                    });
                });
            });

            $('#editar_producto_form').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '../scripts/editar_productos.php',
                    data: formData,
                    success: function (response) {
                        const res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'El producto se ha actualizado correctamente.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                $('#acciones_modal').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al actualizar el producto: ' + res.error,
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    }
                });
            });
        });
    </script>

    <!--

        Modal para agregar un producto nuevo

    -->

    <div class="modal fade" id="agregar_producto_modal" tabindex="-1" aria-labelledby="agregar_producto_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agregar_producto_modal_label" style="color: #000;">Agregar productos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoria-form" class="form mb-3">
                        <div class="mb-3">
                            <label for="categoria_nuevo_producto" class="form-label" style="color: #000;">Selecciona categoria</label>
                            <select name="categoria_nuevo_producto" id="categoria_nuevo_producto" class="form-select" required>
                                <option value="">Seleccionar categoria</option>
                                <?php
                                    $consulta = "select categoria_producto.categoria as cat from categoria_producto";
                                    $db = new database();
                                    $categorias = $db->seleccionar($consulta);

                                    foreach ($categorias as $categoria) 
                                    {
                                        echo "<option value='{$categoria->cat}'>{$categoria->cat}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <input type="button" id="confirmar-categoria" class="btn btn-success" value="Confirmar categoria">
                    </form>

                    <form id="producto-form" class="form" method="POST" style="display: none;">
                        <input type="hidden" name="categoria_nuevo_producto" id="categoria_nuevo_producto_hidden">
                        <div class="mb-3">
                            <label for="nombre_producto" class="form-label" style="color: #000;">Nombre del producto</label>
                            <input type="text" name="nombre_producto"  required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" id="nombre_producto" class="form-control" required>
                        </div>

                        <div class="mb-3" id="marca-div">
                            <label for="marca_producto" class="form-label" style="color: #000;">Marca</label>
                            <select name="marca_producto" id="marca_producto" class="form-select" required>
                                <option value="">Seleccionar marca</option>
                                <?php
                                    $consulta = "select marcas.marca from marcas;";
                                    $db = new database();
                                    $marcas = $db->seleccionar($consulta);
                                    foreach ($marcas as $marca) {
                                        echo "<option value='{$marca->marca}'>{$marca->marca}</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3" id="color_producto_div">
                            <label for="color_producto" class="form-label" style="color: #000;">Color</label>
                            <input type="text"  required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" name="color_producto" id="color_producto" class="form-control">
                        </div>

                        <div class="mb-3" id="tamano_producto_div">
                            <label for="tamano_producto" class="form-label" style="color: #000;">Tamaño</label>
                            <input type="number" name="tamano_producto"  required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"  id="tamano_producto" class="form-control" min="0">
                        </div>

                        <div class="mb-3" id="ram_producto_div">
                            <label for="ram_producto" class="form-label" style="color: #000;">Memoria RAM</label>
                            <input type="number" name="ram_producto" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"  id="ram_producto" class="form-control" min="0">
                        </div>

                        <div class="mb-3" id="almacenamiento_producto_div">
                            <label for="almacenamiento_producto" class="form-label" style="color: #000;">Almacenamiento</label>
                            <input type="number" name="almacenamiento_producto"  required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" id="almacenamiento_producto" class="form-control" min="0">
                        </div>

                        <div class="mb-3" id="material_producto_div">
                            <label for="material_producto" class="form-label" style="color: #000;">Material</label>
                            <input type="text" name="material_producto" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" id="material_producto" class="form-control">
                        </div>

                        <div class="mb-3" id="compatibilidad_producto_div">
                            <label for="compatibilidad_producto" class="form-label" style="color: #000;">Compatibilidad</label>
                            <input type="text" name="compatibilidad_producto"  required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" id="compatibilidad_producto" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="precio_producto" class="form-label" style="color: #000;">Precio</label>
                            <input type="number" name="precio_producto" id="precio_producto" class="form-control" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion_corta" class="form-label" style="color: #000;">Descripcion corta</label>
                            <textarea name="descripcion_corta"  required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" id="descripcion_corta" class="form-control" maxlength="64" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion_detallada" class="form-label" style="color: #000;">Descripcion en detalle</label>
                            <textarea name="descripcion_detallada"  required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" id="descripcion_detallada" class="form-control" maxlength="420" required></textarea>
                        </div>
                        
                        <input type="button" id="crear-producto" class="btn btn-success" value="Crear nuevo producto"> 
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmarCategoriaBtn = document.getElementById('confirmar-categoria');
        const categoriaSelect = document.getElementById('categoria_nuevo_producto');
        const categoriaHiddenInput = document.getElementById('categoria_nuevo_producto_hidden');
        const productoForm = document.getElementById('producto-form');

        const campos = {
            'color_producto_div': document.getElementById('color_producto_div'),
            'tamano_producto_div': document.getElementById('tamano_producto_div'),
            'ram_producto_div': document.getElementById('ram_producto_div'),
            'almacenamiento_producto_div': document.getElementById('almacenamiento_producto_div'),
            'material_producto_div': document.getElementById('material_producto_div'),
            'compatibilidad_producto_div': document.getElementById('compatibilidad_producto_div')
        };

        function toggleCampo(campo, mostrar) {
            if (mostrar) {
                campos[campo].style.display = 'block';
            } else {
                campos[campo].style.display = 'none';
            }
        }

        confirmarCategoriaBtn.addEventListener('click', function () {
            if (categoriaSelect.value !== '') {
                confirmarCategoriaBtn.style.display = 'none';
                categoriaSelect.disabled = true;
                categoriaHiddenInput.value = categoriaSelect.value;

                // Mostrar el formulario de producto
                productoForm.style.display = 'block';

                // Petición AJAX para obtener las opciones basadas en la categoría
                fetch('../scripts/opciones_producto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'categoria=' + encodeURIComponent(categoriaSelect.value)
                })
                .then(response => response.json())
                .then(data => {
                    toggleCampo('color_producto_div', data.color == 1);
                    toggleCampo('tamano_producto_div', data.tamano == 1);
                    toggleCampo('ram_producto_div', data.ram == 1);
                    toggleCampo('almacenamiento_producto_div', data.almacenamiento == 1);
                    toggleCampo('material_producto_div', data.material == 1);
                    toggleCampo('compatibilidad_producto_div', data.compatibilidad == 1);
                })
                .catch(error => console.error('Error:', error));
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor selecciona una categoría antes de continuar.',
                    confirmButtonText: 'Aceptar'
                });
            }
        });

        // Inicialmente ocultar todos los campos adicionales
        for (const campo in campos) {
            campos[campo].style.display = 'none';
        }
    });
    </script>

    <script>
        document.getElementById('crear-producto').addEventListener('click', function() {
            const productoForm = document.getElementById('producto-form');
            const precioProducto = document.getElementById('precio_producto').value;

            if (precioProducto === '' || precioProducto === null) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'El precio del producto es requerido.'
                });
                return;
            }

            const formData = new FormData(productoForm);

            fetch('../scripts/nuevo_producto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(responseText => {
                if (responseText.includes("Error al crear el producto")) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: responseText
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: responseText
                    });
                    $('#agregar_producto_modal').modal('hide');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al crear el producto: ' + error.message
                });
            });
        });

        document.getElementById('agregar_producto_modal').addEventListener('hidden.bs.modal', function () {
            const categoriaForm = document.getElementById('categoria-form');
            categoriaForm.reset();
            document.getElementById('categoria_nuevo_producto').disabled = false;
            document.getElementById('confirmar-categoria').style.display = 'block';

            const productoForm = document.getElementById('producto-form');
            productoForm.reset();
            productoForm.style.display = 'none';

            const optionalInputs = ['ram_producto_div', 'almacenamiento_producto_div', 'color_producto_div', 'tamano_producto_div', 'material_producto_div', 'compatibilidad_producto_div'];
            optionalInputs.forEach(divId => {
                const divElement = document.getElementById(divId);
                divElement.style.display = 'none';
                const inputElement = divElement.querySelector('input');
                if (inputElement) {
                    inputElement.value = '';
                    inputElement.removeAttribute('required');
                }
            });
        });
    </script>


    <!--

        Modal para agregar una nueva categoria

    -->

    <div class="modal fade" id="agregar_categoria_modal" tabindex="-1" aria-labelledby="agregar_categoria_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agregar_categoria_modal_label" style="color: #000;">Agrega categoria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoria_form" class="form" onsubmit="return procesarFormulario(event)">
                        <div class="mb-3">
                            <label for="nueva_categoria" class="form-label" style="color: #000;">Nueva categoria</label>
                            <input type="text" class="form-control" id="nueva_categoria" placeholder="Categoria" name="nueva_categoria" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="color: #000;">Opciones</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="ram" name="opciones[]" value="ram">
                                <label class="form-check-label" for="ram" style="color: #000;">Memoria RAM</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="almacenamiento" name="opciones[]" value="almacenamiento">
                                <label class="form-check-label" for="almacenamiento" style="color: #000;">Almacenamiento</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="color" name="opciones[]" value="color">
                                <label class="form-check-label" for="color" style="color: #000;">Color</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tamano" name="opciones[]" value="tamano">
                                <label class="form-check-label" for="tamano" style="color: #000;">Tamaño</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="material" name="opciones[]" value="material">
                                <label class="form-check-label" for="material" style="color: #000;">Material</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="compatibilidad" name="opciones[]" value="compatibilidad">
                                <label class="form-check-label" for="compatibilidad" style="color: #000;">Compatibilidad</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Crear categoria</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function procesarFormulario(event) {
            event.preventDefault();

            const form = document.getElementById('categoria_form');
            const formData = new FormData(form);

            const opciones = formData.getAll('opciones[]');
            if (opciones.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor, elige al menos una opción.',
                    confirmButtonText: 'Aceptar'
                });
                return false;
            }

            const datos = {
                p_nombre_categoria: formData.get('nueva_categoria'),
                p_ram: opciones.includes('ram') ? 1 : 0,
                p_almacenamiento: opciones.includes('almacenamiento') ? 1 : 0,
                p_color: opciones.includes('color') ? 1 : 0,
                p_tamano: opciones.includes('tamano') ? 1 : 0,
                p_material: opciones.includes('material') ? 1 : 0,
                p_compatibilidad: opciones.includes('compatibilidad') ? 1 : 0
            };

            fetch('../scripts/crear_categoria.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload();
                    });
                } else if (data.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud.',
                    confirmButtonText: 'Aceptar'
                });
            });
        }

        document.getElementById('categoria_form').addEventListener('submit', procesarFormulario);

        document.getElementById('agregar_categoria_modal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('categoria_form').reset();
        });
    </script>

    <!--

        Modal para agregar una nueva marca

    -->

    <div class="modal fade" id="agregar_marca_modal" tabindex="-1" aria-labelledby="agregar_marca_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agregar_marca_modal_label" style="color: #000;">Agregar Marca</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="marcaForm" class="form">
                        <div class="mb-3">
                            <label for="nueva_marca" class="form-label" style="color: #000;">Marca</label>
                            <input type="text" id="nueva_marca" name="nueva_marca" class="form-control" placeholder="Nombre de la marca">
                        </div>
                        <input type="submit" class="btn btn-success" value="Insertar marca">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#marcaForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '../scripts/insertar_marca.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Marca insertada con éxito',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else if (response.startsWith('error:')) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.replace('error:', ''),
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            });
        });
    </script>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>