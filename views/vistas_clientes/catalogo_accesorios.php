<?php
session_start();
include '../../class/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesorios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
            .modal-header{
            background-color: #000;
        }
        .modal-body{
            background-color: #242424;
            color: #fff;
        }
        .navbar {
            background-color: #000;
        }
        .navbar-nav {
            text-align: center;
            width: 100%;
            justify-content: center;
        }
        .navbar-nav .nav-item {
            display: inline-block;
        }
        .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-nav .nav-link:hover {
            color: #96e52e;
            border-bottom: 1px solid #96e52e;
        }
        .navbar-brand {
            color: #fff;
        }
        .navbar-brand:hover {
            color: #fff;
        }
        .dropdown-menu {
            background-color: #000;
        }
        .dropdown-menu .dropdown-item {
            color: #fff;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #444;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
        }
        .card-custom {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .card-body-custom {
            flex: 1; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-body-custom img {
            max-width: 100%;
            height: auto;
        }
        .thumbnail {
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        .thumbnail.active {
            opacity: 1;
        }
        .card-custom {
            min-height: 200px;
        }
        .collapse-container {
            width: 100%;
        }
        .fixed-button-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        .whatsapp-button {
            background-color: #25D366;
            border: none;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .whatsapp-button:hover {
            background-color: #20c35b;
        }
        .whatsapp-button img {
            width: 20px;
            height: 20px;
        }
        body {
            background-color: #242424;
        }
        h3{
            color: #96e52e;
        }
        p, h1, h2, h4, h5, h6, label{
            color: #fff;
        }
        .logo {
            width:9%;
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
            border-bottom: 5px solid #fff;
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
        .buscar-accesorio{
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }
        .buscar-accesorio:focus{
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }
        input[type="search"]::-webkit-input-placeholder {
            color: #bfbfbf;
        }
                        .card-body-custom h3,
                .card-body-custom h4,
                .card-body-custom h5,
                .card-body-custom p {
                    color: #000;
                }

            
                .collapse .card-body h2,
                .collapse .card-body h4,
                .collapse .card-body p {
                    color: #000; 
                }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top sticky-sm-top sticky-lg-top">
        <div class="container-fluid">
        <img class="logo" src="../../img/logo.png" alt="">
            <button data-bs-theme="dark" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #ccc;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./catalogo_celulares.php">Celulares</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Accesorios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./acerca_de.php">Acerca de</a>
                    </li>
                </ul>
                <?php
                    $conexion = new database();
                    $conexion->conectarDB();

                    if (isset($_SESSION["usuario"]))
                    {
                        echo 
                        '
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 d-flex justify-content-start align-items-center">
                                    <a href="./carrito.php">
                                        <img src="../../img/carrito.png" alt="Carro de compras" style="height: 40px; margin-right: 5px;">
                                    </a>
                                </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="dropdown" style="margin-left: 10px;">
                                        <button class="btn btn-success dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Usuario: ' . htmlspecialchars($_SESSION["usuario"]) . '
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="userDropdown">';
                                        $user = $_SESSION["usuario"];
                                        $consulta = "call roles_usuario('$user');";
                                        $roles = $conexion->seleccionar($consulta);

                                        if ($roles) 
                                        {
                                            foreach ($roles as $rol) 
                                            {
                                                switch ($rol->rol) 
                                                {
                                                    case 'Administrador':
                                                        echo '<li><a class="dropdown-item" href="../administrador.php">Administrador</a></li>';
                                                        break;
                                                    case 'Tecnico':
                                                        echo '<li><a class="dropdown-item" href="../vista_empleado_tecnico.php">Tecnico</a></li>';
                                                        break;
                                                    case 'Mostrador':
                                                        echo '<li><a class="dropdown-item" href="../vista_empleado_mostrador.php">Mostrador</a></li>';
                                                        break;
                                                    case 'Cliente':
                                                        echo '<li><a class="dropdown-item" href="./mi_cuenta.php">Mi cuenta</a></li>';
                                                        echo '<li><a class="dropdown-item" href="./notificaciones.php">Notificaciones</a></li>';
                                                        break;
                                                }
                                            }
                                        } 
                                        else 
                                        {
                                            echo '<li><a class="dropdown-item" href="#">No roles found</a></li>';
                                        }

                                        echo '
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="../../scripts/cerrar_sesion.php">Cerrar sesión</a></li>
                                    </ul>
                                    </div>
                                </div>
                            </div>
                        ';
                    } 
                    else 
                    {
                        echo '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" style="white-space: nowrap">Iniciar sesión</button>';
                    }

                    echo '
                            </ul>
                        </div>
                    </div>
                    </div>';
                ?>
            </div>
        </div>
    </nav>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Iniciar sesión</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="../../scripts/verificar_sesion.php" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Nombre de usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="mb-3">
                            <input type="submit" name="login" id="login" class="btn btn-success form-control" value="Iniciar sesión">
                        </div>
                    </form>
                    <div class="container">
                        <p>Aun no tienes cuenta? <a href="./registro.php">Crea una</a> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() 
        {
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', function(event) 
            {
                event.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, 
                {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => 
                {
                    if (data.includes('Inicio de sesión exitoso.')) 
                    {
                        Swal.fire
                        ({
                            icon: 'success',
                            title: 'Inicio de sesión exitoso.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => 
                        {
                            window.location.reload();
                        });
                    } 
                    else 
                    {
                        Swal.fire
                        ({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error en el inicio de sesión. Por favor, verifique sus credenciales.'
                        });
                        form.reset();
                    }
                })
                .catch(error => 
                {
                    console.error('Error:', error);
                });
            });
        });
    </script>

    <img src="../../img/banner2.jpg" class="d-block w-100" alt="...">

    <div class="container mt-3" style="display: flex; justify-content: center; align-items: center;">
        <h1 style="font-size: 64px; margin: auto; text-align: center;" class="mt-3 mb-3">Catalogo de accesorios</h1>
    </div>

    <script src="../../scripts/scriptbusqueda_accesorios.js"></script>

    <div class="container mb-3 mt-3">
        <div class="col-lg-12 col-sm-12 mt-3">
            <label for="filtro_busqueda" class="form-label">Buscar por nombre</label>
            <input type="search" class="form-control buscar-accesorio" id="filtro_busqueda" onkeyup="consulta_buscador($('#filtro_busqueda').val());" oninput="consulta_buscador(this.value);" name="filtro_busqueda" placeholder="Buscar accesorio">
            <div class="card_busqueda position-absolute" id="card_busqueda" style="opacity: 0;">
                <div class="card shadow-sm p-2">
                    <div class="container m-0 p-0" id="resultados_busqueda_nav">
                    </div>
                </div>
            </div>
        </div>
        <h3 class="mt-3">Filtros</h3>
        <form action="" method="POST" id="filtroForm">
            <div class="row" style="width: 100%;">
                    <select name="filtro_catalogo" id="filtro_catalogo" class="form-select filtro" style="display: none;">
                        <option value="">Seleccione opción</option>
                    </select>
                <div class="col-lg-2 col-sm-12 mt-3">
                    <label for="filtro_marca" class="form-label">Marcas</label>
                    <select name="filtro_marca" id="filtro_marca" class="form-select filtro">
                        <option value="">Seleccione opción</option>
                        <?php
                            $consulta = "select * from marcas_accesorios;";
                            $marcas = $conexion->seleccionar($consulta);
                            foreach ($marcas as $marca) 
                            {
                                echo "<option value='{$marca->marca}'>{$marca->marca}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12 mt-3">
                    <label for="filtro_precio" class="form-label">Filtrar por precio</label>
                    <select name="filtro_precio" id="filtro_precio" class="form-select filtro">
                        <option value="">Seleccione opción</option>
                        <option value="mayor-menor">Precio mayor a menor</option>
                        <option value="menor-mayor">Precio menor a mayor</option>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12 mt-3">
                    <label for="filtro_categoria" class="form-label">Categorías</label>
                    <select name="filtro_categoria" id="filtro_categoria" class="form-select filtro">
                        <option value="">Seleccione opción</option>
                        <?php
                            $consulta = "select * from categorias_accesorios";
                            $categorias = $conexion->seleccionar($consulta);
                            foreach ($categorias as $categoria) 
                            {
                                echo "<option value='{$categoria->id}'>{$categoria->categoria}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12 mt-3">
                    <label for="filtro_color" class="form-label">Color</label>
                    <select name="filtro_color" id="filtro_color" class="form-select filtro">
                        <option value="">Seleccione opción</option>
                        <?php
                            $consulta = 'select * from colores_accesorios';
                            $colores = $conexion->seleccionar($consulta);
                            foreach ($colores as $color) 
                            {
                                echo "<option value='{$color->color}'>{$color->color}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn-success btn" style="width: 15vw;" onclick="filtrar()">Filtrar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function filtrar() 
        {
            var filtro_busqueda = document.getElementById('filtro_busqueda').value;

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            function addField(name, value) 
            {
                if (value) 
                {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    form.appendChild(input);
                }
            }

            addField('filtro_busqueda', document.getElementById('filtro_busqueda').value);
        addField('filtro_catalogo', document.getElementById('filtro_catalogo').value);
        addField('filtro_marca', document.getElementById('filtro_marca').value);
        addField('filtro_precio', document.getElementById('filtro_precio').value);
        addField('filtro_categoria', document.getElementById('filtro_categoria').value);
        addField('filtro_color', document.getElementById('filtro_color').value);

        document.body.appendChild(form);
        form.submit();
        }
    </script>

                <?php
                    $filtro_marca = null;
                    $filtro_precio = null;
                    $filtro_categoria = null;
                    $filtro_color = null;
                    $filtro_busqueda = null;

                        $filtro_marca = isset($_POST['filtro_marca']) ? $_POST['filtro_marca'] : null;
                        $filtro_precio = isset($_POST['filtro_precio']) ? $_POST['filtro_precio'] : null;
                        $filtro_categoria = isset($_POST['filtro_categoria']) ? $_POST['filtro_categoria'] : null;
                        $filtro_color = isset($_POST['filtro_color']) ? $_POST['filtro_color'] : null;
                        $filtro_busqueda = isset($_POST['filtro_busqueda']) ? $_POST['filtro_busqueda'] : null;
                            // OFSET PARA LAS PAGINAS DE CATALOGO OMG
                            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

                            $offset = ($pagina - 1) * 16;

                        $consulta = "SELECT * FROM catalogo_accesorios_existencias WHERE 1=1";

                        if ($filtro_marca) 
                        {
                            $consulta = "call filtro_marca_accesorios ('$filtro_marca');";
                        }
                        
                        if ($filtro_categoria) 
                        {
                            $consulta .= " AND categoria = $filtro_categoria";
                        }
                        
                        if ($filtro_color) 
                        {
                            $consulta .= " AND color = '$filtro_color'";
                        }
                        if ($filtro_busqueda) {
                            $consulta .= " AND producto LIKE '%$filtro_busqueda%'";
                        }
                        if ($filtro_precio) 
                        {
                            if ($filtro_precio === 'mayor-menor') 
                            {
                                $consulta = "select * from ca_precio_mayor_menor";
                            } 
                            else if ($filtro_precio === 'menor-mayor') 
                            {
                                $consulta = "select * from ca_precio_menor_mayor";
                            }
                        }
                        $consulta .= " LIMIT ? OFFSET ?";
                        
                        $params = [16, $offset];

                        $celulares = $conexion->seleccionarparapaginas($consulta,$params);

                        
                    
                        // del catalogo de celulares, ya sabes , pa las pagians akkaaskdljsakldjzlxc
                        $totalpaginas = "SELECT COUNT(*) as total FROM catalogo_accesorios";
                        $paginastotal = $conexion->seleccionar($totalpaginas);
                      
                        $totalProductos = $paginastotal[0]->total; 
                      
                        $productosPorPagina = 16; 
                      
                      
                        $PAGINAS = ceil($totalProductos / $productosPorPagina);
                        if ($celulares) {

                        $base_url = '../..';

                        echo '<div class="container">';
                        echo '<div class="row">';

                        foreach ($celulares as $index => $celular) 
                        {
                            if ($index % 4 === 0) 
                            {
                                echo '<div class="row justify-content-center">';
                            }
                            
                            $collapseId = "collapse" . $index;
                            $id_celular = $celular->id_producto;
                            
                            

                            $consulta_imagen = $conexion->seleccionar("SELECT * FROM imagenes_productos WHERE producto = :id", ['id' => $id_celular]);
                            $imagen_dir = $consulta_imagen ? $consulta_imagen[0]->url : ''; 
                            $imagen_url = $base_url . htmlspecialchars($imagen_dir, ENT_QUOTES, 'UTF-8');
                            


                            $consulta_imagen2 = $conexion->seleccionar("SELECT * FROM productos WHERE id_producto = :id", ['id' => $id_celular]);
                            $imagen_dir2 = $consulta_imagen2 ? $consulta_imagen2[0]->img : ''; 
                            $imagen_url2 = $base_url . htmlspecialchars($imagen_dir2, ENT_QUOTES, 'UTF-8');


                            echo '<!-- Ruta de la imagen: ' . htmlspecialchars($imagen_dir, ENT_QUOTES, 'UTF-8') . ' -->';
                            
                            echo '<div class="col-lg-3 col-sm-6 mb-3 mt-3">';
                            echo '<div class="card card-custom">';
                            echo '<div class="card-body card-body-custom d-flex flex-column">';
                            echo '<img src="' . htmlspecialchars($imagen_url2, ENT_QUOTES, 'UTF-8') . '" alt="Imagen del producto" class="img-fluid mb-2" style="width: 100%; height: auto;">';
                            echo '<h3>' . htmlspecialchars($celular->marca) . '</h3>';
                            echo '<h4>' . htmlspecialchars($celular->producto) . '</h4>';
                            echo '<h5>$' . htmlspecialchars($celular->precio) . '</h5>';
                            echo '<p>' . htmlspecialchars($celular->db) . '</p>';
                            echo '<button class="btn btn-success mt-auto collapse-button" type="button" data-bs-toggle="collapse" data-bs-target="#' . $collapseId . '" aria-expanded="false" aria-controls="' . $collapseId . '">Ver detalles</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                            echo '<div class="col-12 d-lg-none">';
                            echo '<div class="collapse mt-2 mb-2" id="' . $collapseId . '">';
                            echo '<div class="card card-body">';
                                echo '<div class="row">';
                                echo '<div class="row">';
                                echo '<div class="col-sm-12 d-lg-none">';
                                echo '
                                    <div id="carouselExample" class="carousel slide">
                                        <div class="carousel-inner">';

                                        $consulta_imagenes = $conexion->seleccionar("SELECT * FROM imagenes_productos WHERE producto = :id", ['id' => $celular->id_producto]);

                                        $activeClass = 'active'; 
                                        foreach ($consulta_imagenes as $imagen) 
                                        {
                                            $imagen_url = $base_url . htmlspecialchars($imagen->url, ENT_QUOTES, 'UTF-8');
                                            echo '<div class="carousel-item ' . $activeClass . '">';
                                            echo '<img src="' . $imagen_url . '" class="d-block w-100" alt="Imagen del producto">';
                                            echo '</div>';
                                            $activeClass = ''; 
                                        }

                                echo '
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                ';
                                echo '<div class="col-sm-12 d-lg-none">'; 
                                echo '<h2>' . htmlspecialchars($celular->marca) . '</h2>';
                                echo '<h4>' . htmlspecialchars($celular->producto) . '</h4>';
                                echo '<h4>$' . htmlspecialchars($celular->precio) . '</h4>';
                                echo '<p><strong>Stock:</strong> ' . htmlspecialchars($celular->stock) . '</p>';

                                if (!empty($celular->ram)) 
                                {
                                    echo '<p><strong>RAM:</strong> ' . htmlspecialchars($celular->ram) . 'GB</p>';
                                }
                                
                                if (!empty($celular->almacenamiento)) 
                                {
                                    echo '<p><strong>Almacenamiento:</strong> ' . htmlspecialchars($celular->almacenamiento) . 'GB</p>';
                                }
                                
                                if (!empty($celular->tamano)) 
                                {
                                    echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($celular->tamano) . ' pulgadas</p>';
                                }
                                
                                if (!empty($celular->conectividad)) 
                                {
                                    echo '<p><strong>Compatibilidad (cable):</strong> ' . htmlspecialchars($celular->conectividad) . '</p>';
                                }
                                
                                if (!empty($celular->dl)) 
                                {
                                    echo '<p><strong>Descripción:</strong><br>' . htmlspecialchars($celular->dl) . '</p>';
                                }                                

                                
                                echo '</div>'; 
                                echo '</div>'; 

                                        if (isset($_SESSION['usuario'])) 
                                        {
                                            echo '
                                            <form id="form_agregar_carrito_' . $id_celular . '" action="../../scripts/agregar_carrito.php" method="POST" class="form form-agregar-carrito">
                                                <input type="hidden" name="id_producto" value="' . htmlspecialchars($celular->id_producto) . '">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <input type="number" id="cantidad" name="cantidad" min="1" max="2" step="1" class="form-control" value="1">
                                                    </div>
                                                    <div class="col-9">
                                                        <button type="button" class="btn btn-success btn-submit" style="width: 100%;" onclick="agregarAlCarrito(' . $id_celular . ')">Agregar producto al carrito</button>
                                                    </div>
                                                </div>
                                            </form>';
                                        }
                                        else
                                        {
                                            echo '
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5 style="color: #000;">Puedes añadir el producto al carrito iniciando sesión</h5>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" style="white-space: nowrap; width: 100%;">Iniciar sesión</button>
                                                </div>
                                            </div>';
                                        }
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                            if ($index % 4 === 3 || $index === count($celulares) - 1) 
                            {
                                echo '</div>'; 

                                echo '<div class="row justify-content-center d-none d-lg-flex">';
                                for ($i = $index - ($index % 4); $i <= $index; $i++) 
                                {
                                    echo '</div>'; 

                                echo '<div class="row justify-content-center d-none d-lg-flex">';
                                for ($i = $index - ($index % 4); $i <= $index; $i++) 
                                {
                                    $collapseId = "collapse" . $i;
                                    echo '<div class="col-12 collapse-container">';
                                    echo '<div class="collapse mt-2 mb-2" id="' . $collapseId . '">';
                                    echo '<div class="card card-body">';
                                    echo '<div class="row">';
                                    echo '<div class="col-lg-4">';
                                    echo '<div id="carousel' . $celulares[$i]->id_producto . '" class="carousel slide" data-bs-ride="carousel">';
                                    echo '<div class="carousel-indicators">';
                        
                                    $consulta_imagenes = $conexion->seleccionar("SELECT * FROM imagenes_productos WHERE producto = :id", ['id' => $celulares[$i]->id_producto]);
                        
                                    foreach ($consulta_imagenes as $imgIndex => $imagen) 
                                    {
                                        $activeClass = $imgIndex === 0 ? 'active' : '';
                                        echo '<button type="button" data-bs-target="#carousel' . $celulares[$i]->id_producto . '" data-bs-slide-to="' . $imgIndex . '" class="' . $activeClass . '" aria-current="true" aria-label="Slide ' . ($imgIndex + 1) . '"></button>';
                                    }
                                    echo '</div>';
                                    echo '<div class="carousel-inner">';

                                    foreach ($consulta_imagenes as $imgIndex => $imagen) 
                                    {
                                        $imagen_url = $base_url . htmlspecialchars($imagen->url, ENT_QUOTES, 'UTF-8');
                                        $activeClass = $imgIndex === 0 ? 'active' : '';
                                        echo '<div class="carousel-item ' . $activeClass . '">';
                                        echo '<img src="' . $imagen_url . '" class="d-block w-100" alt="Imagen del producto">';
                                        echo '</div>';
                                    }

                                    echo '</div>';
                                    echo '<button class="carousel-control-prev" type="button" data-bs-target="#carousel' . $celulares[$i]->id_producto . '" role="button" data-bs-slide="prev">';
                                    echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                                    echo '<span class="visually-hidden">Previous</span>';
                                    echo '</button>';
                                    echo '<button class="carousel-control-next" type="button" data-bs-target="#carousel' . $celulares[$i]->id_producto . '" role="button" data-bs-slide="next">';
                                    echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                                    echo '<span class="visually-hidden">Next</span>';
                                    echo '</button>';
                                    echo '</div>';
                                    echo '</div>';
                                            echo '<div class="col-lg-8">';
                                                echo '<h2>' . htmlspecialchars($celulares[$i]->marca) . '</h2>';
                                                echo '<h4>' . htmlspecialchars($celulares[$i]->producto) . '</h4>';
                                                echo '<h4>$' . htmlspecialchars($celulares[$i]->precio) . '</h4>';
                                                echo '<p><strong>Stock:</strong> ' . htmlspecialchars($celular->stock) . '</p>';
                                                
                                                if (!empty($celulares[$i]->ram)) 
                                                {
                                                    echo '<p><strong>RAM: </strong> ' . htmlspecialchars($celulares[$i]->ram) . 'GB</p>';
                                                }
                                                
                                                if (!empty($celulares[$i]->almacenamiento)) 
                                                {
                                                    echo '<p><strong>Almacenamiento: </strong> ' . htmlspecialchars($celulares[$i]->almacenamiento) . 'GB</p>';
                                                }
                                                
                                                if (!empty($celulares[$i]->material)) 
                                                {
                                                    echo '<p><strong>Material: </strong> ' . htmlspecialchars($celulares[$i]->material) . ' pulgadas</p>';
                                                }

                                                if (!empty($celulares[$i]->tamano)) 
                                                {
                                                    echo '<p><strong>Tamaño: </strong> ' . htmlspecialchars($celulares[$i]->tamano) . ' pulgadas</p>';
                                                }
                                                
                                                if (!empty($celulares[$i]->conectividad)) 
                                                {
                                                    echo '<p><strong>Compatibilidad: </strong> ' . htmlspecialchars($celulares[$i]->conectividad) . '</p>';
                                                }
                                                
                                                if (!empty($celulares[$i]->dl)) 
                                                {
                                                    echo '<p><strong>Descripción: </strong><br>' . htmlspecialchars($celulares[$i]->dl) . '</p>';
                                                }                                                

                                                if (isset($_SESSION['usuario']))
                                                {
                                                    echo '
                                                    <form id="form_agregar_carrito_' . htmlspecialchars($celulares[$i]->id_producto) . '" action="../../scripts/agregar_carrito.php" method="POST" class="form form-agregar-carrito">
                                                        <input type="hidden" name="id_producto" value="' . htmlspecialchars($celulares[$i]->id_producto) . '">
                                                        <div class="row">
                                                            <div class="col-3" style= "display:none">
                                                                <input type="number" id="cantidad" name="cantidad" min="1" max="2" step="1" class="form-control" value="1">
                                                            </div>
                                                            <div class="col-9">
                                                                <button type="button" class="btn btn-success btn-submit" style="width: 100%;" onclick="agregarAlCarrito(' . htmlspecialchars($celulares[$i]->id_producto) . ')">Agregar 1 producto al carrito</button>
                                                            </div>
                                                        </div>
                                                    </form>';
                                                }
                                                else
                                                {
                                                    echo '
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                           <h5 style="color: #000;">Puedes añadir el producto al carrito iniciando sesión</h5>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" style="white-space: nowrap; width: 100%;">Iniciar sesión</button>
                                                        </div>
                                                    </div>';
                                                }
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                        }
                    }

                        echo '</div>';
                        echo '</div>';
                        echo '<div style="text-align: center; margin-top: 20px;">'; 

                        for ($i = 1; $i <= $PAGINAS; $i++) {
                            $active = ($i == $pagina) ? 'active' : '';
                            echo "<a href='catalogo_celulares.php?pagina=$i' class='btn btn-success $active mb-3 mt-3' style='margin: 0 5px;'>$i</a> ";
                        }
                        
                        echo '</div>'; 
                
                    }
                
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function agregarAlCarrito(idProducto) 
                {
                    const form = document.getElementById("form_agregar_carrito_" + idProducto);
                    const formData = new FormData(form);

                    fetch(form.action, 
                    {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => 
                    {
                        if (data.status === "success") 
                        {
                            Swal.fire
                            ({
                                icon: "success",
                                title: "Éxito",
                                text: data.message,
                            });
                        } 
                        else 
                        {
                            Swal.fire
                            ({
                                icon: "error",
                                title: "Error",
                                text: data.message,
                            });
                        }
                    })
                    .catch(error => 
                    {
                        Swal.fire
                        ({
                            icon: "error",
                            title: "Error",
                            text: "No se pueden insertar mas de dos de un mismo celular.",
                        });
                    });
                }

                document.addEventListener("DOMContentLoaded", function() 
                {
                    var collapseButtons = document.querySelectorAll(".collapse-button");
                    collapseButtons.forEach(function(button) 
                    {
                        button.addEventListener("click", function() 
                        {
                            var collapseElements = document.querySelectorAll(".collapse");
                            collapseElements.forEach(function(collapse) 
                            {
                                if (collapse !== document.querySelector(button.getAttribute("data-bs-target"))) 
                                {
                                    var collapseInstance = new bootstrap.Collapse(collapse, 
                                    {
                                        toggle: false
                                    });
                                    collapseInstance.hide();
                                }
                            });
                        });
                    });
                });
            </script>
        </div>
    </div>

    <div class="fixed-button-container">
        <button class="whatsapp-button" onclick="window.location.href='https://wa.me/1234567890'">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
        </button>
    </div>

    <footer class="footer" style="padding: 20px; margin-top: auto; background-color: #000; color: white; position: relative; bottom: 0; width: 100%;">
        <div class="container text-center">
            <h3 class="mb-3 mt-3" style="color: #96e52e;">Frankarcell</h3>
            <div class="d-flex justify-content-center mb-4">
                <a href="./catalogo_celulares.php" class="text-white mx-3" style="text-decoration: none;">
                    <i class="fas fa-mobile-alt"></i> Celulares
                </a>
                <a href="./servicios.php" class="text-white mx-3" style="text-decoration: none;">
                    <i class="fas fa-tools"></i> Servicios
                </a>
                <a href="https://wa.me/1234567890" class="text-white mx-3" style="text-decoration: none;">
                    <i class="fab fa-whatsapp"></i> Contáctanos
                </a>
            </div>
            <p class="small mb-0">© 2024 Frankarcell. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>