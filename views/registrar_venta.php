<?php
session_start();

if (!isset($_SESSION["usuario"])) 
{
    header("Location: ../index.php");
    exit();
}

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
        if ($rol->rol == 'Administrador' || $rol->rol == 'Mostrador') 
        {
            $permiso = true;
            break;
        }
    }
}

if (!$permiso) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar nueva venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .autocomplete-items {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 20%;
        }
        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-item:hover {
            background-color: #ddd;
        }
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
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
    </style>
</head>
<body>
    <div class="container mt-3 mb-3">
        <?php
            foreach ($roles as $rol) 
            {
                if ($rol->rol == 'Administrador') 
                {
                    echo '<button type="button" class="btn btn-success mb-3 mt-3" onclick="window.location.href=\'./admin_control_ventas.php\'">Volver</button>';
                    break;
                } 
                elseif ($rol->rol == 'Mostrador') 
                {
                    echo '<button type="button" class="btn btn-success mb-3 mt-3" onclick="window.location.href=\'./vista_empleado_mostrador.php\'">Volver</button>';
                    break;
                }
            }
        ?>
        <h1>Registrar nueva venta</h1>
        <div class="main-content">
            <div class="row">
                <div class="col-lg-4 col-sm-12">
                    <form id="venta-form" action="" class="form" method="POST">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="show-search" />
                            <label class="form-check-label" for="show-search">Cliente registrado</label>
                        </div>
                        <div class="mb-3" id="search-container" style="display: none;">
                            <label for="cliente" class="form-label">Nombre de usuario</label>
                            <input id="search-box" type="text" name="cliente" class="form-control" placeholder="Nombre de Usuario">
                            <ul id="autocomplete-results" class="autocomplete-items"></ul>
                        </div>
                        <input type="submit" class="btn btn-success mb-3" value="Crear venta">
                    </form>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
                    {
                        $cliente = !empty($_POST['cliente']) ? $_POST['cliente'] : NULL;

                        if (isset($user)) 
                        {
                            $consulta = "call nueva_venta ('$user', '$cliente')";
                            $conexion->seleccionar($consulta);

                            $consulta = "select * from conseguir_numero_venta;";
                            $resultados = $conexion->seleccionar($consulta);

                            if ($resultados && count($resultados) > 0) 
                            {
                                $numero_venta = $resultados[0];
                                $_SESSION['numero_venta'] = $numero_venta->numero_venta;
                                $_SESSION['form_state'] = 'number';
                                header("Location: {$_SERVER['PHP_SELF']}");
                                exit();
                            } 
                            else 
                            {
                                if (isset($conexion->error) && $conexion->error) 
                                {
                                    echo "Error en la consulta SQL: " . $conexion->error;
                                } 
                                else 
                                {
                                    echo "No se encontró ningún número de venta.";
                                }
                            }
                        } 
                        else 
                        {
                            echo "El usuario no está definido.";
                        }
                    }

                    $numero_venta = isset($_SESSION['numero_venta']) ? $_SESSION['numero_venta'] : null;
                    
                    if ($numero_venta) 
                    {
                        echo "<div id='numero-venta'><h4>Número de venta: <br>" . htmlspecialchars($numero_venta) . "</h4></div>";
                        unset($_SESSION['numero_venta']);
                        $_SESSION['form_state'] = 'form';
                    ?>
                        <form id="insert-product-form" action="" method="post" class="form">
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categorias</label>
                                <select name="categoria" id="categoria" class="form-select">
                                    <option value="">Seleccione categoria</option>
                                    <?php
                                    $consulta = "SELECT categoria_producto.categoria AS cat FROM categoria_producto";
                                    $categorias = $conexion->seleccionar($consulta);

                                    foreach ($categorias as $categoria) {
                                        echo "<option value='{$categoria->cat}'>{$categoria->cat}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="producto" class="form-label">Productos</label>
                                <select name="producto" id="producto" class="form-select">
                                    <option value="">Seleccione un producto</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" max="0">
                            </div>
                            <div class="mb-3">
                                <input type="submit" class="btn btn-success" value="Insertar producto">
                            </div>
                        </form>

                        <div class="justify-content-end mb-3">
                            <form id="terminar-venta-form" action="../scripts/terminar_venta.php" method="POST" class="form">
                                <input type="hidden" id="numero_venta" name="numero_venta" value="<?php echo htmlspecialchars($numero_venta); ?>">
                                <input type="submit" class="btn btn-danger" value="Terminar venta">
                            </form>
                        </div>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            $(document).ready(function() 
                            {
                                $('#terminar-venta-form').submit(function(event) 
                                {
                                    event.preventDefault(); 

                                    $.ajax({
                                        url: $(this).attr('action'),
                                        method: 'POST',
                                        data: $(this).serialize(), 
                                        success: function(response) 
                                        {
                                            var data = JSON.parse(response);
                                            if (data.success) {
                                                Swal.fire(
                                                {
                                                    title: 'Éxito',
                                                    text: data.message,
                                                    icon: 'success'
                                                }
                                                ).then(() => 
                                                {
                                                    location.reload();
                                                });
                                            } 
                                            else 
                                            {
                                                Swal.fire(
                                                {
                                                    title: 'Error',
                                                    text: data.message,
                                                    icon: 'error'
                                                });
                                            }
                                        },
                                        error: function() 
                                        {
                                            Swal.fire(
                                            {
                                                title: 'Error',
                                                text: 'Error al enviar la solicitud.',
                                                icon: 'error'
                                            });
                                        }
                                    });
                                });
                            });
                        </script>

                        <script>
                            $(document).ready(function() 
                            {
                                $('#categoria').change(function() 
                                {
                                    var categoria = $(this).val();
                                    $.ajax({
                                        url: '../scripts/obtener_productos.php',
                                        method: 'POST',
                                        data: { categoria: categoria },
                                        success: function(data) 
                                        {
                                            $('#producto').html('<option value="">Seleccione un producto</option>' + data);
                                            $('#cantidad').val('');
                                        }
                                    });
                                });

                                $('#producto').change(function() 
                                {
                                    var producto_id = $(this).val();
                                    if (producto_id) 
                                    {
                                        $.ajax(
                                        {
                                            url: '../scripts/obtener_stock.php',
                                            method: 'POST',
                                            data: { producto_id: producto_id },
                                            success: function(data) 
                                            {
                                                var stock = parseInt(data, 10);
                                                if (!isNaN(stock) && stock > 0) 
                                                {
                                                    $('#cantidad').prop('max', stock);
                                                } 
                                            }
                                        });
                                    } 
                                    else 
                                    {
                                        $('#cantidad').prop('disabled', true);
                                    }
                                });

                                $('#cantidad').on('input', function() 
                                {
                                    var max = parseInt($(this).prop('max'), 10);
                                    var value = parseInt($(this).val(), 10);

                                    if (value > max) 
                                    {
                                        $(this).val(max);
                                    } 
                                    else if (value < 1) 
                                    {
                                        $(this).val(1);
                                    }
                                });

                                $('#insert-product-form').submit(function(event) {
                                event.preventDefault();

                                var categoria = $('#categoria').val();
                                var producto = $('#producto').val();
                                var cantidad = $('#cantidad').val();
                                var numeroVenta = <?php echo json_encode($numero_venta); ?>;

                                if (producto && cantidad && numeroVenta) {
                                    $.ajax({
                                        url: '../scripts/insertar_producto_venta.php',
                                        method: 'POST',
                                        data: {
                                            numero_venta: numeroVenta,
                                            id_producto: producto,
                                            cantidad: cantidad
                                        },
                                        success: function(response) {
                                            var data = JSON.parse(response);
                                            if (data.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Éxito',
                                                    text: 'Producto insertado con éxito.',
                                                    allowOutsideClick: false,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $('#insert-product-form')[0].reset(); // Limpiar los valores del formulario
                                                        $('#producto').html('<option value="">Seleccione un producto</option>');
                                                        cargarProductosVenta(numeroVenta); // Recargar la tabla de productos
                                                    }
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: 'Stock insuficiente.',
                                                    allowOutsideClick: false,
                                                });
                                            }
                                        },
                                        error: function() {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Error al enviar la solicitud.',
                                                allowOutsideClick: false,
                                            });
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Advertencia',
                                        text: 'Por favor, complete todos los campos.',
                                        allowOutsideClick: false,
                                    });
                                }
                            });

                                $('#finish-sale').click(function() {
                                    Swal.fire({
                                        title: '¿Está seguro de que desea terminar la venta?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Sí, terminar',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            Swal.fire(
                                                'Venta terminada',
                                                'La venta se ha completado con éxito.',
                                                'success'
                                            );
                                        }
                                    });
                                });

                            });
                        </script>
                    <?php
                    }
                    ?>
                </div>

                <div class="col-lg-8 col-sm-12">
                    <h2>Ticket</h2>
                    <div class="table_wrapper">
                        <table id="productos-venta" class="table table-hover">
                            <thead class="table-success">
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() 
        {
        var numeroVenta = <?php echo json_encode($numero_venta); ?>;
        if (numeroVenta) {
            cargarProductosVenta(numeroVenta);
        }

        $(document).on('click', '.btn-eliminar', function() {
            var idProducto = $(this).data('id-producto');
            Swal.fire({
                title: '¿Está seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../scripts/eliminar_producto_venta.php',
                        method: 'POST',
                        data: {
                            numero_venta: numeroVenta,
                            id_producto: idProducto
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire(
                                    'Eliminado!',
                                    'El producto ha sido eliminado.',
                                    'success'
                                );
                                cargarProductosVenta(numeroVenta); // Recargar la tabla de productos
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Error al eliminar el producto: ' + data.error,
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Error al enviar la solicitud.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>


    <script>
        function cargarProductosVenta(numeroVenta) {
            $.ajax({
                url: '../scripts/obtener_productos_venta.php',
                method: 'POST',
                data: { numero_venta: numeroVenta },
                success: function(data) {
                    $('#productos-venta tbody').html(data);
                },
                error: function() {
                    alert('Error al cargar los productos de la venta.');
                }
            });
        }

        // Cargar la tabla de productos si ya hay una venta en curso al cargar la página
        $(document).ready(function() {
            var numeroVenta = <?php echo json_encode($numero_venta); ?>;
            if (numeroVenta) {
                cargarProductosVenta(numeroVenta);
            }
        });
    </script>

    <script>
        document.getElementById('show-search').addEventListener('change', function() {
            var searchContainer = document.getElementById('search-container');
            if (this.checked) {
                searchContainer.style.display = 'block';
            } else {
                searchContainer.style.display = 'none';
            }
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
                        item.className = 'autocomplete-item';
                        item.addEventListener('click', function() {
                            document.getElementById('search-box').value = user.nombre_usuario;
                            document.getElementById('autocomplete-results').innerHTML = '';
                        });
                        autocompleteResults.appendChild(item);
                    });
                })
                .catch(error => console.error('Error fetching autocomplete data:', error));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
