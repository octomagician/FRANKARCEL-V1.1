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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .autocomplete-items {
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            position: absolute;
            background-color: #fff;
            width: 93%;
            color: #000;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-active {
            background-color: #e9e9e9;
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
        .form-control{
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
        .modal-body, 
.modal-header,
.modal-body label, 
.modal-body input, 
.modal-body textarea, 
.modal-body select,
.modal-body option, 
.modal-title {
    color: #fff;
}

    </style>
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center position-relative" style="height: 100vh;">
        <button type="button" class="btn btn-secondary position-absolute top-0 start-0 m-2 mt-3" onclick="window.location.href='../index.php'">Volver al inicio</button>
        <h2 class="mb-4">Mostrador</h2>
        <div class="mb-3">
            <button type="button" class="btn btn-success" onclick="window.location.href='./registrar_venta.php'">Registrar nueva venta</button>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-success" onclick="window.location.href='./completar_venta_carrito.php'">Completar venta de carrito</button>
        </div>
        <div class="mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOrderModal">Agregar nueva orden</button>
        </div>
        <div class="mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#asignar_dispositivo">Asignar dispositivo</button>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-success" onclick="window.location.href='./cancelar_entregar_orden.php'">Cancelar o entregar orden</button>
        </div>
    </div>



    <!-- Modal para Generar nueva orden -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOrderModalLabel" style="color: #000;">Generar nueva orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addOrderForm" method="post">
                        <div class="mb-3">
                            <label for="usuario" class="form-label" style="color: #000;">Nombre de usuario</label>
                            <input id="search-box" type="text" name="cliente" class="form-control" placeholder="Nombre de Usuario" required>
                            <ul id="autocomplete-results" class="autocomplete-items"></ul>
                        </div>
                        <div class="mb-3">
                            <button type="button" id="confirmarUsuarioBtn" class="btn btn-success" style="color: #000;">Confirmar Usuario</button>
                        </div>
                        <div class="mb-3" id="dispositivosDiv" style="display: none;">
                            <label for="dispositivo" class="form-label" style="color: #000;">Dispositivo</label>
                            <select id="dispositivo" name="dispositivo" class="form-select" required>
                                <option value="">Seleccione dispositivo</option>
                            </select>
                        </div>
                        <div class="mb-3" id="tipoServicioDiv" style="display: none;">
                            <?php
                            $consulta = "select * from tipo_servicio;";
                            $reg = $conexion->seleccionar($consulta);
                            echo 
                            '
                                <label for="tipo_servicio" class="form-label" style="color: #000;">Tipo servicio</label>
                                <select name="tipo_servicio" id="tipo_servicio" class="form-select" required>
                                    <option value="">Seleccionar tipo de servicio</option>';
                            foreach ($reg as $value) 
                            {
                                echo "<option value='{$value->tipo_servicio}'>{$value->tipo_servicio}</option>";
                            }
                            echo '</select>';
                            ?>
                        </div>
                        <div class="mb-3" id="serviciosDiv" style="display: none;">
                            <label for="servicio" class="form-label" style="color: #000;">Servicio</label>
                            <select id="servicio" name="servicio" class="form-select" required>
                                <option value="">Seleccione servicio</option>
                            </select>
                        </div>
                        <div class="mb-3" id="descripcionDiv" style="display: none;">
                            <label for="descripcion" class="form-label" style="color: #000;">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3" id="submitDiv" style="display: none;">
                            <input type="submit" class="btn btn-success" value="Generar Orden">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Asignar dispositivo -->
    <div class="modal fade" id="asignar_dispositivo" tabindex="-1" aria-labelledby="asignar_dispositivo_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asignar_dispositivo_label" style="color: #000;">Asignar dispositivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="asignarDispositivoForm" method="post">
                        <div class="mb-3">
                            <label for="usuario_asignar" class="form-label" style="color: #000;">Nombre de usuario</label>
                            <input id="search-box-asignar" type="text" name="cliente_asignar" class="form-control" placeholder="Nombre de Usuario" required>
                            <ul id="autocomplete-results-asignar" class="autocomplete-items"></ul>
                        </div>
                        <div class="mb-3">
                            <button type="button" id="confirmarUsuarioBtnAsignar" class="btn btn-success">Confirmar Usuario</button>
                        </div>
                        <div class="mb-3" id="marcaDivAsignar" style="display: none;">
                            <label for="marca_asignar" class="form-label" style="color: #000;">Marca</label>
                            <select name="marca_asignar" id="marca_asignar" class="form-select" required>
                                <option value="">Seleccione marca</option>
                                <?php
                                    $consulta = "select marcas.id_marca, marcas.marca from marcas";
                                    $marcas = $conexion->seleccionar($consulta);
                                    foreach ($marcas as $marca) 
                                    {
                                        echo '<option value="' . htmlspecialchars($marca->id_marca) . '">' . htmlspecialchars($marca->marca) . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3" id="dispositivoDivAsignar" style="display: none;">
                            <label for="asignar_dispositivo" class="form-label" style="color: #000;">Dispositivo</label>
                            <input name="asignar_dispositivo" id="asignar_dispositivo" type="text" class="form-control" required maxlength="128">
                        </div>
                        <div class="mb-3" id="submitDivAsignar" style="display: none;">
                            <input type="submit" class="btn btn-success" value="Asignar Dispositivo" name="as_dis">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if (isset($_POST['as_dis']))
        {
            $cliente = $_POST['cliente_asignar'];
            $marca = $_POST['marca_asignar'];
            $dispositivo = $_POST['asignar_dispositivo'];

            $consulta = "call asignar_dispositivo_user ('$cliente', $marca, '$dispositivo')";
            $resultado = $conexion->seleccionar($consulta);

            
            echo 
            '
                <script>
                    Swal.fire
                    ({
                        icon: "success",
                        title: "Dispositivo asignado",
                        text: "El dispositivo ha sido asignado exitosamente."
                    }).then(function() 
                    {
                        window.location.href = "./vista_empleado_mostrador.php";
                    });
                </script>';
        }
    }
    ?>

    <script>
        document.getElementById('confirmarUsuarioBtn').addEventListener('click', function() {
            var usuario = document.getElementById('search-box').value;

            fetch('../scripts/buscar_dispositivos.php', 
            {
                method: 'POST',
                headers: 
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'usuario=' + encodeURIComponent(usuario)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Error en la respuesta del servidor');
                }

                let dispositivoSelect = document.getElementById('dispositivo');
                dispositivoSelect.innerHTML = '<option value="">Seleccione dispositivo</option>';
                data.dispositivos.forEach(dispositivo => {
                    let option = document.createElement('option');
                    option.value = dispositivo.id;
                    option.textContent = dispositivo.dispositivo;
                    dispositivoSelect.appendChild(option);
                });
                document.getElementById('dispositivosDiv').style.display = 'block';
                document.getElementById('tipoServicioDiv').style.display = 'block';
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Hubo un problema al procesar la solicitud.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        });

        document.getElementById('tipo_servicio').addEventListener('change', function() {
            let tipoServicio = this.value;
            fetch('../scripts/buscar_servicios.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'tipo_servicio=' + encodeURIComponent(tipoServicio)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    throw new Error(data.message || 'Error en la respuesta del servidor');
                }

                let servicioSelect = document.getElementById('servicio');
                servicioSelect.innerHTML = '<option value="">Seleccione servicio</option>';
                data.servicios.forEach(servicio => {
                    let option = document.createElement('option');
                    option.value = servicio.servicio;
                    option.textContent = servicio.servicio;
                    servicioSelect.appendChild(option);
                });
                document.getElementById('serviciosDiv').style.display = 'block';
                document.getElementById('descripcionDiv').style.display = 'block';
                document.getElementById('submitDiv').style.display = 'block';
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Hubo un problema al procesar la solicitud.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        });

        document.getElementById('addOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var form = event.target;
            var formData = new FormData(form);

            fetch('../scripts/procesar_orden.php', 
            {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '¡Orden Generada!',
                        text: 'La nueva orden ha sido creada exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) 
                        {
                            location.reload(); 
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al procesar la solicitud.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        });

        
        document.getElementById('search-box').addEventListener('input', function() {
            let input = this.value;
            if (input.length < 3) {
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
                            autocompleteResults.innerHTML = '';
                        });
                        autocompleteResults.appendChild(item);
                    });
                });
        });

        document.getElementById('search-box-asignar').addEventListener('input', function() {
            let input = this.value;
            if (input.length < 3) {
                document.getElementById('autocomplete-results-asignar').innerHTML = '';
                return;
            }

            fetch('../scripts/autocompletar.php?query=' + input)
                .then(response => response.json())
                .then(data => {
                    let autocompleteResultsAsignar = document.getElementById('autocomplete-results-asignar');
                    autocompleteResultsAsignar.innerHTML = '';
                    data.forEach(user => {
                        let item = document.createElement('div');
                        item.textContent = user.nombre_usuario;
                        item.addEventListener('click', function() {
                            document.getElementById('search-box-asignar').value = user.nombre_usuario;
                            autocompleteResultsAsignar.innerHTML = '';
                        });
                        autocompleteResultsAsignar.appendChild(item);
                    });
                });
        });

        document.getElementById('confirmarUsuarioBtn').addEventListener('click', function() {
            document.getElementById('dispositivosDiv').style.display = 'block';
            document.getElementById('tipoServicioDiv').style.display = 'block';
            document.getElementById('serviciosDiv').style.display = 'block';
            document.getElementById('descripcionDiv').style.display = 'block';
            document.getElementById('submitDiv').style.display = 'block';
        });

        document.getElementById('confirmarUsuarioBtnAsignar').addEventListener('click', function() {
            document.getElementById('marcaDivAsignar').style.display = 'block';
            document.getElementById('dispositivoDivAsignar').style.display = 'block';
            document.getElementById('submitDivAsignar').style.display = 'block';
        });
    </script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>