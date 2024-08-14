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
        if ($rol->rol == 'Tecnico') 
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
    <title>Tecnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        input[type="number"]::-webkit-input-placeholder {
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
        <div class="d-flex justify-content-between mt-3">
            <a href="../index.php" class="btn btn-secondary">Volver al inicio</a>
            <a href="./notificaciones_empleados.php" class="btn btn-success">Notificaciones</a>
        </div>
        <button id="toggleButton" class="btn btn-success hidden" style="display: none; pointer-events: none;">Menu</button>
        <div class="main-content" style="margin-top: 35px;">
            <h2>Actualización de servicios</h2>

        <!--

            Codigo para crear el form de filtrado segun estados de servicios

        -->

            <form action="" method='POST' class="d-flex flex-column">
                <div class="mb-3">
                    <label for="buscar_orden" class="form-label" style="color: #000;">Número de orden</label>
                    <input type="number" min="1" step="1" name="buscar_orden" id="buscar_orden" class="form-control" required>
                </div>
                <input type="submit" class="btn btn-success mt-2" value="Buscar orden">
            </form>

        <!--

            Codigo para la tabla que se muestra en la pagina

        -->
            <div class="table_wrapper">
                <table class='table mt-3 table-hover'>
                    <thead class='table-success'>
                        <tr>
                            <th>Orden</th>
                            <th>Servicio</th>
                            <th>Tipo servicio</th>
                            <th>Dispositivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estado_servicio'])) 
                        {
                            $estado_servicio = $_POST['estado_servicio'];
                            $consulta = "call filtro_estados_orden('$estado_servicio')";
                            $estados = $conexion->seleccionar($consulta);

                            if ($estados) 
                            {
                                foreach ($estados as $reg) 
                                {
                                    echo "<tr>";
                                    echo "<td>" . (isset($reg->id) ? $reg->id : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->servicio) ? $reg->servicio : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->tipo_servicio) ? $reg->tipo_servicio : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->dispositivo) ? $reg->dispositivo : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->estado) ? $reg->estado : 'N/A') . "</td>";
                                    echo '<td>';
                                    if ($reg->estado == "Por empezar" || $reg->estado == "En reparacion") 
                                    {
                                        echo '<button type="button" class="btn btn-success actualizar-servicio-btn" data-id="' . (isset($reg->id) ? $reg->id : 'N/A') . '" data-bs-toggle="modal" data-bs-target="#detalleModal">Actualizar servicio</button>';
                                    }
                                    echo 
                                    '
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#informacion_actualizaciones">Historial actualizaciones</button>
                                    ';
                                    echo '</td>';
                                    echo "</tr>";
                                }
                            } 
                            else 
                            {
                                echo "<tr><td colspan='7'>No se encontraron servicios para este estado.</td></tr>";
                            }
                        } 
                        else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_orden'])) 
                        {
                            $orden = $_POST['buscar_orden'];
                            $consulta = "call buscar_orden_por_numero($orden)";
                            $estado_orden = $conexion->seleccionar($consulta);

                            if ($estado_orden) 
                            {
                                foreach ($estado_orden as $reg) 
                                {
                                    echo "<tr>";
                                    echo "<td>" . (isset($reg->id) ? $reg->id : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->servicio) ? $reg->servicio : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->tipo_servicio) ? $reg->tipo_servicio : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->dispositivo) ? $reg->dispositivo : 'N/A') . "</td>";
                                    echo "<td>" . (isset($reg->estado) ? $reg->estado : 'N/A') . "</td>";
                                    echo '<td>';
                                    if ($reg->estado == "Por empezar" || $reg->estado == "En reparacion") 
                                    {
                                        echo '<button type="button" class="btn btn-success actualizar-servicio-btn" data-id="' . (isset($reg->id) ? $reg->id : 'N/A') . '" data-bs-toggle="modal" data-bs-target="#detalleModal" style="margin-right: 10px;">Actualizar servicio</button>';
                                    }
                                    echo '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#informacion_actualizaciones">Historial actualizaciones</button>';
                                    echo '</td>';
                                    echo "</tr>";
                                }
                            } 
                            else 
                            {
                                echo "<tr><td colspan='7'>No se encontraron servicios para esta orden.</td></tr>";
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!--

            Modal para actualizar el estado de un servicio

        -->

        <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleModalLabel" style="color: #000;">Detalles del Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <form id="form_orden" class="form">
                                <div id="identificar_orden_div" class="mb-3">
                                    <label for="orden_identificar" class="form-label" style="color: #000;">Número de orden</label>
                                    <input name="orden_identificar" id="orden_identificar" type="number" class="form-control mb-3" min="1" required readonly>
                                    <input type="button" id="confirmar_identificar_btn" class="btn btn-success" value="Confirmar orden">
                                </div>
                                
                                <div id="detalle_campos" style="display: none;">
                                    <div id="resultado_procedimiento"></div>
                                    <div class="mb-3">
                                        <label for="estados_actualizar" class="form-label" style="color: #000;">Estados</label>
                                        <select name="estados_actualizar" id="estados_actualizar" class="form-select">
                                            <option value="">Seleccionar opción</option>
                                        </select>
                                    </div>
                                    <div class="mb-3" id="descripcion_div">
                                        <label for="actualizacion_descripcion" class="form-label" style="color: #000;">Descripción</label>
                                        <textarea name="actualizacion_descripcion" id="actualizacion_descripcion" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3" id="precio_div">
                                        <label for="actualizacion_precio" class="form-label" style="color: #000;">Precio</label>
                                        <input type="number" class="form-control" name="actualizacion_precio" min="0" step="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <input type="button" id="actualizar_orden_btn" class="btn btn-success" value="Actualizar orden">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Manejar el clic en los botones de actualización del servicio
                document.querySelectorAll('.actualizar-servicio-btn').forEach(button =>
                 {
                    button.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-id');
                        document.getElementById('orden_identificar').value = orderId;
                    });
                });
                // ola xd
                // Manejar el clic en el botón de confirmar identificación
                document.getElementById('confirmar_identificar_btn').addEventListener('click', function()
                 {
                    const orderId = document.getElementById('orden_identificar').value;

                    if (!orderId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Por favor, ingrese un número de orden válido.'
                        });
                        return;
                    }

                    this.style.display = 'none';
                    document.getElementById('detalle_campos').style.display = 'block';

                    // Llamada AJAX para obtener los estados
                    fetch('../scripts/identificar_estados.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ orden: orderId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Agregar las opciones al select de estados
                        let estadosSelect = document.getElementById('estados_actualizar');
                        estadosSelect.innerHTML = '<option value="">Seleccionar opción</option>';

                        if (data.error) {
                            console.error('Error:', data.error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al obtener los estados.'
                            });
                            return;
                        }

                        data.forEach(row => {
                            let option = document.createElement('option');
                            option.value = row.estado;
                            option.text = row.estado;
                            estadosSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al conectar con el servidor.'
                        });
                    });
                });

                // Manejar el clic en el botón de actualizar orden
                document.getElementById('actualizar_orden_btn').addEventListener('click', function(event) {
                    event.preventDefault(); // Evita el envío del formulario

                    const orderId = document.getElementById('orden_identificar').value;
                    const estado = document.getElementById('estados_actualizar').value;
                    const descripcion = document.getElementById('actualizacion_descripcion').value;
                    const precio = document.querySelector('input[name="actualizacion_precio"]').value;

                    if (!orderId || !estado || !descripcion || !precio) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Por favor, complete todos los campos.'
                        });
                        return;
                    }

                    // Llamada AJAX para actualizar el estado
                    fetch('../scripts/actualizar_estado.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            orden: orderId,
                            estado: estado,
                            descripcion: descripcion,
                            precio: precio
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Orden actualizada exitosamente'
                            }).then(() => {
                                // Cerrar modal y recargar la página
                                $('#detalleModal').modal('hide'); // Cierra el modal con jQuery y Bootstrap
                                location.reload(); // Recarga la página
                            });
                        } else {
                            console.error('Error:', data.error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al actualizar la orden.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al conectar con el servidor.'
                        });
                    });
                });
            });
        </script>
    
        <!--
        
            Modal para ver el historial de actualizaciones de una orden

        -->

        <div class="modal fade" id="informacion_actualizaciones" tabindex="-1" aria-labelledby="informacion_actualizaciones_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="informacion_actualizaciones_label" style="color: #000;">Historial actualizaciones</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form">
                            <div class="mb-3">
                                <label for="historial_orden" class="form-label" style="color: #000;">Número de orden</label>
                                <input name="historial_orden" id="historial_orden" type="number" class="form-control mb-3" min="1" readonly>
                                <input type="button" id="confirmar_historial_btn" class="btn btn-success" value="Confirmar orden">
                            </div>
                        </form>
                        <div id="informacion_adicional">
                            
                        </div>
                        <table id="historial_table" class="table table-hover" style="display:none;">
                            <thead class="table-success">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Descripcion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('sidebar-hidden');
            document.querySelector('.content').classList.toggle('content-expanded');
            document.querySelector('.content').classList.toggle('content-collapsed');
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
                            document.getElementById('autocomplete-results').innerHTML = '';
                            showDeviceAndServiceDropdowns(user.nombre_usuario);
                        });
                        autocompleteResults.appendChild(item);
                    });
                });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function(){
            $('body').on('click', 'button[data-bs-target="#informacion_actualizaciones"]', function(){
                var ordenId = $(this).closest('tr').find('td').eq(0).text().trim();
                $('#historial_orden').val(ordenId);
            });

            $('#confirmar_historial_btn').click(function(){
                var orden = $('#historial_orden').val();

                if(orden) {
                    $.ajax({
                        url: '../scripts/informacion_orden.php',
                        type: 'POST',
                        data: {orden: orden},
                        dataType: 'json',
                        success: function(response) {
                            if (response.inicio.length > 0) {
                                var mostrador = response.inicio[0].mostrador;
                                var fecha = response.inicio[0].fecha;
                                $('#informacion_adicional').html('<p  style="color: #000;"><strong>Mostrador:</strong> ' + mostrador + '</p><p style="color: #000;"><strong>Fecha:</strong> ' + fecha + '</p>');
                            } else {
                                $('#informacion_adicional').html('<p style="color: #000;">No se encontró información de inicio para la orden.</p>');
                            }
                            
                            // Llamada adicional a ver_historial.php para obtener el historial
                            $.ajax({
                                url: '../scripts/ver_historial.php',
                                type: 'POST',
                                data: {orden: orden},
                                dataType: 'json',
                                success: function(response) {
                                    if (response.historial.length > 0) {
                                        var rows = '';
                                        response.historial.forEach(function(item){
                                            rows += '<tr><td>' + item.fecha + '</td><td>' + item.estado + '</td><td>' + item.descripcion + '</td></tr>';
                                        });
                                        $('#historial_table tbody').html(rows);
                                    } else {
                                        $('#historial_table tbody').html('<tr><td colspan="3">No se encontró historial de actualizaciones para la orden.</td></tr>');
                                    }

                                    $('#confirmar_historial_btn').hide();
                                    $('#historial_table').show();
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error en la solicitud AJAX: ", status, error);
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud AJAX: ", status, error);
                        }
                    });
                } else {
                    alert("Por favor, ingrese un número de orden válido.");
                }
            });

            $('#informacion_actualizaciones').on('hidden.bs.modal', function () {
                $('#historial_orden').val('');
                $('#informacion_adicional').html('');
                $('#confirmar_historial_btn').show();
                $('#historial_table').hide();
                $('#historial_table tbody').html('<tr></tr>');
            });
        });
    </script>

</body>
</html>