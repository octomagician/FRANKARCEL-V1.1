<?php
session_start();
include '../../class/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celulares</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .filtro_busqueda{
            color: #000 ;
        }
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
                        <a class="nav-link" href="#">Celulares</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./catalogo_accesorios.php">Accesorios</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes('Inicio de sesión exitoso.')) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Inicio de sesión exitoso.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error en el inicio de sesión. Por favor, verifique sus credenciales.'
                        });
                        form.reset();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>

    <img src="../../img/banner3.jpg" class="d-block w-100" alt="...">

    <div class="container mt-3" style="display: flex; justify-content: center; align-items: center;">
        <h1 style="font-size: 64px; margin: auto; text-align: center;" class="mt-3 mb-3">Catalogo de celulares</h1>
    </div>

    <div class="container mb-3 mt-3">
        <div class="col-lg-12 col-sm-12 mt-3">
                <label for="filtro_busqueda" class="form-label">Buscar por nombre</label>
                <input type="text" name="filtro_busqueda" id="filtro_busqueda" class="form-control buscar-producto" placeholder="Buscar producto..."onkeyup="consulta_buscador($('#filtro_busqueda').val());" oninput="consulta_buscador(this.value);">
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
                            $consulta = "select * from marcas_celulares;";
                            $marcas = $conexion->seleccionar($consulta);
                            foreach ($marcas as $marca) {
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
                    <label for="filtro_ram" class="form-label">RAM</label>
                    <select name="filtro_ram" id="filtro_ram" class="form-select filtro">
                        <option value="">Seleccione opción</option>
                        <?php
                            $consulta = 'select * from ram';
                            $rams = $conexion->seleccionar($consulta);
                            foreach ($rams as $ram) {
                                echo "<option value='{$ram->ram}'>{$ram->ram} GB</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12 mt-3">
                    <label for="filtro_rom" class="form-label">Almacenamiento</label>
                    <select name="filtro_rom" id="filtro_rom" class="form-select filtro">

                        <option value="">Seleccione opción</option>
                        <?php
                            $consulta = 'select * from rom';
                            $roms = $conexion->seleccionar($consulta);
                            foreach ($roms as $rom) {
                                echo "<option value='{$rom->rom}'>{$rom->rom} GB</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12 mt-3">
                    <label for="filtro_color" class="form-label">Color</label>
                    <select name="filtro_color" id="filtro_color" class="form-select filtro">
                        <option value="">Seleccione opción</option>
                        <?php
                            $consulta = 'select * from color';
                            $colores = $conexion->seleccionar($consulta);
                            foreach ($colores as $color) {
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
        // definimos la funcion
function filtrar() {
    // obtiene el valor del campo que tenga la id filtro_busqueda, osea todos los fultros de arriba jaja ola comoestan gente
    // crea un formulario dinamico wtf
    //crea el elemento form en DOM
    // le asigna el metodo post, como su action esta vacio nos envia a la misma pagina donde estamos, when no haces nada
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    
    //addfield 
    function addField(name, value)
     {
        // value es el valor del filtro busqueda
        if (value) 
        {
            //crea un input
            var input = document.createElement('input');
            //ese input no lo muestra al usuario
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            //campo de entrada del formulario
            form.appendChild(input);

        }
    }
    //justamente llama a la funcion y le da un nombre y el valor de filtrobusqueda
    addField('filtro_busqueda', document.getElementById('filtro_busqueda').value);
    addField('filtro_catalogo', document.getElementById('filtro_catalogo').value);
    addField('filtro_marca', document.getElementById('filtro_marca').value);
    addField('filtro_precio', document.getElementById('filtro_precio').value);
    addField('filtro_ram', document.getElementById('filtro_ram').value);
    addField('filtro_rom', document.getElementById('filtro_rom').value);
    addField('filtro_color', document.getElementById('filtro_color').value);
    //todo este formulario dinamico se lo da al cuerpo del catalogo, por asi decirlo y lo sube con el metodo post usando sumbit
    document.body.appendChild(form);
    form.submit();

    // fun fuct todo esto se hace en oculto porque no es necesario que sea visible al usuario, simplemente se pasan datos aqui que el usuario no debe ver
}
</script>



<?php
// por el amor de dios que esto esto estara demasiado dificil de explicar en cierto modo, TIREN PAROOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
$filtro_marca = null;
$filtro_precio = null;
$filtro_ram = null;
$filtro_rom = null;
$filtro_color = null;
$filtro_busqueda = null;

    $filtro_marca = isset($_POST['filtro_marca']) ? $_POST['filtro_marca'] : null;
    $filtro_precio = isset($_POST['filtro_precio']) ? $_POST['filtro_precio'] : null;
    $filtro_ram = isset($_POST['filtro_ram']) ? $_POST['filtro_ram'] : null;
    $filtro_rom = isset($_POST['filtro_rom']) ? $_POST['filtro_rom'] : null;
    $filtro_color = isset($_POST['filtro_color']) ? $_POST['filtro_color'] : null;
    $filtro_busqueda = isset($_POST['filtro_busqueda']) ? $_POST['filtro_busqueda'] : null;

// tomando en cuenta lo anterior, todo esto es para inicializar los filtros, sus variables, si existen en la soliticitud post anteriormente iniciada, se le asignan esos valores, si no, se ponen en nmmull

// por explicar
// OFSET PARA LAS PAGINAS DE CATALOGO OMG
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

$offset = ($pagina - 1) * 16;

$consulta = "SELECT * FROM cc_existentes WHERE 1=1 ";
// consulta para ver todo el catalogo obviamente

    
    if ($filtro_marca) 
    {
        $consulta .= " AND marca = '$filtro_marca'";
    }
   
    if ($filtro_ram) 
    {
        $consulta .= " AND ram = '$filtro_ram'";
    }
    if ($filtro_rom) 
    {
        $consulta .= " AND almacenamiento = '$filtro_rom'";
    }
    if ($filtro_color) 
    {
        $consulta .= " AND color = '$filtro_color'";
    }
    if ($filtro_busqueda) 
    {
        $consulta .= " AND producto LIKE '%$filtro_busqueda%'";
    }
    if ($filtro_precio) 
    {
        if ($filtro_precio === 'mayor-menor') 
        {
            $consulta .= " ORDER BY precio DESC";
        } else if ($filtro_precio === 'menor-mayor') 
        {
            $consulta .= " ORDER BY precio ASC";
        }
    }
    //MIRA esto no es tAAAAAAAN COMPLEjo, solamente, si las variables de filtros estan inicializadas, osea no estan nulas como al principio de este codigo php, se le agregaran ANDS a la consulta
    // para que se muestre lo que eligio el usuario en los filtros, aqui reciben sus valores de ya sabe, rojo, rojote , asi, los valores de los filtros

    

    $params = [16, $offset];

    $consulta .= " LIMIT ? OFFSET ?";

    $celulares = $conexion->seleccionarparapaginas($consulta, $params);
  //calcular paginas omg esto es nuevo
  $totalpaginas = "SELECT COUNT(*) as total FROM vista_catalogo_celulares";
  $paginastotal = $conexion->seleccionar($totalpaginas);

  //recordemos que toda consulta se devuelve como una array, asi que tengo que sacar la primera posicion de esta
  $totalProductos = $paginastotal[0]->total; 

  $productosPorPagina = 16; 


  $PAGINAS = ceil($totalProductos / $productosPorPagina);
  $base_url = '../..';

                        echo '<div class="container">';
                        echo '<div class="row">';
                        // ya aca se imprime todo dependiendo de los filtros que tengamos gente, donde caemos gente

                        foreach ($celulares as $index => $celular) 
                        {
                            if ($index % 4 === 0) 
                            {
                                echo '<div class="row justify-content-center">';
                            }
                            
                            $collapseId = "collapse" . $index;
                            $id_celular = $celular->id_producto;
                            
                            
                            // estas son mis consultas de imagenes, algo antiguas pero ya que, aca andan, pa mostrarlas aajaksldjklasdklzmxkld me estoy volviendo loco
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
                                echo '<p><strong>RAM:</strong> ' . htmlspecialchars($celular->ram) . 'GB</p>';
                                echo '<p><strong>Almacenamiento:</strong> ' . htmlspecialchars($celular->almacenamiento) . 'GB</p>';
                                echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($celular->tamano) . ' pulgadas</p>';
                                echo '<p><strong>Compatibilidad (cable):</strong> ' . htmlspecialchars($celular->conectividad) . '</p>';
                                echo '<p><strong>Descripción:</strong><br>' . htmlspecialchars($celular->dl) . '</p>';
                                echo '</div>'; 
                                echo '</div>'; 

                                        if (isset($_SESSION['usuario'])) 
                                        {
                                            echo '
                                            <form id="form_agregar_carrito_' . $id_celular . '" action="../../scripts/agregar_carrito.php" method="POST" class="form form-agregar-carrito">
                                                <input type="hidden" name="id_producto" value="' . htmlspecialchars($celular->id_producto) . '">
                                                <div class="row">
                                                            <div class="col-3" style= "display:none">
                                                        <input type="number" id="cantidad" name="cantidad" min="1" max="2" step="1" class="form-control" value="1">
                                                    </div>
                                                    <div class="col-9">
                                                        <button type="button" class="btn btn-success btn-submit" style="width: 100%;" onclick="agregarAlCarrito(' . $id_celular . ')">Agregar 1 producto al carrito</button>
                                                    </div>
                                                </div>
                                            </form>';
                                        }
                                        else
                                        {
                                            echo '
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5>Puedes añadir el producto al carrito iniciando sesión</h5>
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
                                                echo '<p><strong>Stock: </strong>' . htmlspecialchars($celulares[$i]->stock) . '</p>';
                                                echo '<p><strong>RAM:</strong> ' . htmlspecialchars($celulares[$i]->ram) . 'GB</p>';
                                                echo '<p><strong>Almacenamiento:</strong> ' . htmlspecialchars($celulares[$i]->almacenamiento) . 'GB</p>';
                                                echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($celulares[$i]->tamano) . ' pulgadas</p>';
                                                echo '<p><strong>Compatibilidad (cable):</strong> ' . htmlspecialchars($celulares[$i]->conectividad) . '</p>';
                                                echo '<p><strong>Descripción:</strong><br>' . htmlspecialchars($celulares[$i]->dl) . '</p>';
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
                                                            <h5>Puedes añadir el producto al carrito iniciando sesión</h5>
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
                        echo "<div style='text-align: center;'>"; 
                        // la verdad es que esto es MUCHO codigo, pero es para imprimr cada uno de los celulares, ademas si el index que son los celulares son multiplos de 4 o es el ultimo producto se 
                        //inicia una nueva fila, row, es mucho codigo html en php la verdad, y solo rescata los valores con consultas dependiendo de la id del dispositivo
                        // me sorprende que esto no rompa la pagina en rendimiento la verdad


                        // esto ultimo es mi paginacion ntp
                        for ($i = 1; $i <= $PAGINAS; $i++) {
                            $active = ($i == $pagina) ? 'active' : '';
                            
                            echo "<a href='catalogo_celulares.php?pagina=$i' class='btn btn-success $active mb-3' style='margin: 0 5px;'>$i</a> ";
                        }
                        
                        echo "</div>"; 
                        
                        
                
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
    <script src="./../../scripts/scriptbusqueda.js"></script>

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