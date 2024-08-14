<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Inicio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./views/ver_servicio.php">Ver servicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                </ul>
                
                <?php
                    session_start();
                    if (isset($_SESSION["usuario"])) {
                        echo '
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Usuario: '.$_SESSION["usuario"]. '
                            </button>
                            <ul class="dropdown-menu">';

                                include '../class/database.php';
                                $conexion = new database();
                                $conexion->conectarDB();

                                $user = $_SESSION["usuario"];

                                $consulta = "call roles_usuario('$user');";

                                $roles = $conexion->seleccionar($consulta);

                                if ($roles) {
                                    foreach ($roles as $rol)
                                    {
                                        if ($rol->rol == 'Administrador')
                                        {
                                            echo '<li><a class="dropdown-item" href="../views/administrador.php">admin</a></li>';
                                        }
                                        else if ($rol->rol == 'Tecnico hardware')
                                        {
                                            echo '<li><a class="dropdown-item" href="#">th</a></li>';
                                        }
                                        else if ($rol->rol == 'Tecnico software')
                                        {
                                            echo '<li><a class="dropdown-item" href="#">ts</a></li>';
                                        }
                                        else if ($rol->rol == 'Mostrador')
                                        {
                                            echo '<li><a class="dropdown-item" href="#">mostrador</a></li>';
                                        }
                                        else if ($rol->rol == 'Cliente')
                                        {
                                            echo '<li><a class="dropdown-item" href="#">Cliente</a></li>';
                                        }
                                    }
                                } else {
                                    echo '<li><a class="dropdown-item" href="#">No roles found</a></li>';
                                }

                                echo '
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="./scripts/cerrar_sesion.php">Cerrar sesión</a></li>
                            </ul>
                        </div>';
                    } else {
                        echo '<button class="btn btn-success" onclick="window.location.href=\'./views/inicio_sesion.php\'">Iniciar sesión</button>';
                    }
                ?>    
                
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
