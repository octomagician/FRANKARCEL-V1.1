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

$permiso = false;
if ($roles) 
{
    foreach ($roles as $rol) 
    {
        if ($rol->rol == 'Mostrador') 
        {
            $permiso = true;
            break;
        }
    }
}

if (!$permiso) 
{
    header("Location: ../index.php");
    exit();
}

$dispositivos = [];
$cliente = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'buscar_dispositivos' && !empty($_POST['cliente'])) {
        $cliente = $_POST['cliente'];
        $_SESSION['cliente'] = $cliente; // Guardar el cliente en la sesión
        $consulta = "call buscar_dispositivos('$cliente');";
        $dispositivos = $conexion->seleccionar($consulta);
    } elseif ($_POST['action'] == 'generar_orden' && !empty($_POST['cliente']) && !empty($_POST['dispositivo'])) {
        $params = [
            'p_mostrador' => $user,
            'p_cliente' => $_POST['cliente'],
            'p_dispositivo' => $_POST['dispositivo'],
            'p_tipo_servicio' => $_POST['tipo_servicio'],
            'p_servicio' => $_POST['servicio'],
            'p_descripcion' => $_POST['descripcion']
        ];
        $conexion->ejecutarProcedimiento('nueva_orden', $params);
        unset($_SESSION['cliente']);
    }
} elseif (isset($_SESSION['cliente'])) {
    $cliente = $_SESSION['cliente'];
    $consulta = "call buscar_dispositivos('$cliente');";
    $dispositivos = $conexion->seleccionar($consulta);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar orden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
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
    <div class="container">
        <button id="toggleButton" class="btn btn-success hidden" style="display: none; pointer-events: none;">Menu</button>
        <a href="./vista_empleado_mostrador.php" class="btn btn-secondary mt-3">Regresar</a>
        <div class="main-content" style="margin-top: 35px;">
            <h2>Buscar orden</h2>
            <div>
                <form action="" method='POST' class="d-flex flex-column">
                    <div class="mb-3">
                        <label for="buscar_orden" class="form-label">Número de orden</label>
                        <input type="number" min="1" step="1" name="buscar_orden" id="buscar_orden" class="form-control" required>
                    </div>
                    <input type="submit" class="btn btn-success mt-2" value="Buscar orden">
                </form>
            </div>
            <div class="table_wrapper">
                <table class='table mt-3 table-hover'>
                    <thead class='table-success'>
                        <tr>
                            <th>Tipo servicio</th>
                            <th>Servicio</th>
                            <th>Persona</th>
                            <th>Dispositivo</th>
                            <th>Estado</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_orden'])) 
                        {
                            $orden = $_POST['buscar_orden'];
                            $consulta = "call buscar_orden_mostrador ($orden)";
                            
                            $informacion = $conexion->seleccionar($consulta);

                            foreach ($informacion as $info) 
                            {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($info->ts) . '</td>';
                                echo '<td>' . htmlspecialchars($info->servicio) . '</td>';
                                echo '<td>' . htmlspecialchars($info->nombre_persona) . '</td>';
                                echo '<td>' . htmlspecialchars($info->dispositivo) . '</td>';
                                echo '<td>' . htmlspecialchars($info->estado) . '</td>';
                                echo '<td>$' . (!empty($info->precio) ? htmlspecialchars($info->precio) : '') . '</td>';
                                echo '<td>';
                                if ($info->estado == "Recibido") 
                                {
                                    echo '
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelar_orden">
                                            Cancelar orden
                                        </button>
                                    ';
                                } 
                                else if ($info->estado == "Terminado") 
                                {
                                    echo '
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#entregar_orden">
                                            Entregar orden
                                        </button>
                                    ';
                                } 
                                else 
                                {
                                    echo '';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                    ?>

                    </tbody>
                </table>
            </div>

            <div style="margin: 0 25% 0 25% ">
                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_orden']))
                    {
                        $consulta = "call detalle_actualizacion ($orden)";
                        $descripciones = $conexion->seleccionar($consulta);

                        foreach ($descripciones as $desc)
                        {
                            echo 
                            '
                                <h5>' . htmlspecialchars($desc->estado) . ':</h5>
                                <div class="mb-3 mt-3">
                                    <p>
                                        ' . htmlspecialchars($desc->descripcion) . '
                                    <p>
                                <div>
                            ';
                        }
                    }
                ?>
            </div>
        </div>

        <div class="modal fade" id="entregar_orden" tabindex="-1" aria-labelledby="entregar_orden_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="entregar_orden_label">Entregar orden</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" class="form">
                            <div class="mb-3">
                                <label for="descripcion_entregado" class="form-label">Descripción</label>
                                <textarea name="descripcion_entregado" id="descripcion_entregado" class="form-control"></textarea>
                            </div>
                            <input type="hidden" name="entregar_orden" value="<?php echo htmlspecialchars($orden); ?>">
                            <input type="submit" value="Entregar orden" class="btn btn-success form-control">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entregar_orden']) && !empty($_POST['entregar_orden']) && isset($_POST['descripcion_entregado']))
        {
            $mostrador = $_SESSION['usuario'];
            $orden_entregar = (int)$_POST['entregar_orden'];
            $descripcion_entregado = htmlspecialchars($_POST['descripcion_entregado']);

            $consulta = "call entregar_orden($orden_entregar, '$mostrador', '$descripcion_entregado')";

            $resultado = $conexion->seleccionar($consulta);

            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Orden entregada",
                    text: "La orden ha sido entregada con éxito.",
                    confirmButtonText: "Aceptar"
                });
            </script>';
        }
        ?>

        <div class="modal fade" id="cancelar_orden" tabindex="-1" aria-labelledby="cancelar_orden_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="cancelar_orden_label">Cancelar orden</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" class="form">
                            <div class="mb-3">
                                <label for="descripcion_cancelado" class="form-label">Descripción</label>
                                <textarea name="descripcion_cancelado" id="descripcion_cancelado" class="form-control"></textarea>
                            </div>
                            <input type="hidden" name="cancelar_orden" value="<?php echo htmlspecialchars($orden); ?>">
                            <input type="submit" value="Cancelar orden" class="btn btn-danger form-control">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar_orden']) && !empty($_POST['cancelar_orden']) && isset($_POST['descripcion_cancelado']))
        {
            $mostrador = $_SESSION['usuario'];
            $orden_cancelar = (int)$_POST['cancelar_orden'];
            $descripcion_cancelado = htmlspecialchars($_POST['descripcion_cancelado']);

            $consulta = "call cancelar_orden_mostrador($orden_cancelar, '$mostrador', '$descripcion_cancelado')";

            $conexion->seleccionar($consulta);

            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Orden cancelada",
                    text: "La orden ha sido cancelada.",
                    confirmButtonText: "Aceptar"
                });
            </script>';
        }
        ?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
