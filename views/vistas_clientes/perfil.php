<?php
    session_start();

    $databasePath = realpath(dirname(__FILE__) . '/../../class/database.php');
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

    $cliente = false;

    if ($roles) 
    {
        foreach ($roles as $rol) 
        {
            if ($rol->rol == 'Cliente') 
            {
                $cliente = true;
                break;
            }
        }
    }

    if (!$cliente) 
    {
        header("Location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #242424;
        }
        .footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: auto; 
            width: 100%;
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
            color: #ccc;
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
        .table_wrapper{
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        .btn-success{
            color: #000;
            background-color: #96e52e;    
            font-weight: bold;
            border-radius: 20px;
            font-size: 18px;
        }
        .form-control{
            background-color: #000;
            color: #fff;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <a href="../../index.php" class="btn btn-secondary mb-3">Volver al inicio</a>
        <div class="card">
            <div class="card-header" style="background-color: #242424; color: #fff;">
                <h1>Perfil</h1>
            </div>

            <?php
                $user = $_SESSION['usuario'];
                $consulta = "call buscar_datos ('$user')";
                $perfil = $conexion->seleccionar($consulta);
            ?>

            <div class="card-body" style="background-color: #242424; color: #fff;">
                <!-- cambiar nombre de usuario -->
                <h5 class="card-title">Nombre de usuario</h5>
                <form action="" method="post" class="mb-3">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="cambiar_usuario" name="cambiar_usuario" required value="<?php echo htmlspecialchars($perfil[0]->nu); ?>">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" name="actualizar_usuario">Actualizar</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_usuario'])) 
                    {
                        $nuevo_user = $_POST['cambiar_usuario'];
                        $consulta = "call buscar_nombre_usuario_cambio('$nuevo_user')";
                        $count = $conexion->seleccionar($consulta);

                        if ($count[0]->count == 0) 
                        {
                            $consulta = "call cambiar_nombre_usuario('$user', '$nuevo_user')";
                            $success = $conexion->seleccionar($consulta);
                            
                            echo 
                            '
                                <script>
                                    Swal.fire
                                    ({
                                        icon: "success",
                                        title: "Usuario actualizado",
                                        text: "Se cerrara la sesión."
                                    }).then(function() {
                                        window.location.href = "../../scripts/cerrar_sesion.php";
                                    });
                                </script>
                            ';
                        } 
                        else 
                        {
                            echo 
                            '
                                <script>
                                    Swal.fire
                                    ({
                                        icon: "error",
                                        title: "Error",
                                        text: "Error al actualizar el usuario"
                                    });
                                </script>
                            ';
                        }
                    }
                ?>



                <div class="alert alert-warning" role="alert">
                    Se cerrara la sesión al cambiar el nombre de usuario.
                </div>

                <!-- Cambiar Nombre y Apellidos -->
                <h5 class="card-title">Datos personales</h5>
                <form action="" method="post" class="mb-3">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="cambiar_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="cambiar_nombre" name="cambiar_nombre" required value="<?php echo htmlspecialchars($perfil[0]->nombre); ?>">
                            </div>
                            <div class="col-lg-4">
                                <label for="cambiar_ap" class="form-label">Apellido paterno</label>
                                <input type="text" class="form-control" id="cambiar_ap" name="cambiar_ap" required value="<?php echo htmlspecialchars($perfil[0]->ap); ?>">
                            </div>
                            <div class="col-lg-4">
                                <label for="cambiar_am" class="form-label">Apellido materno</label>
                                <input type="text" class="form-control" id="cambiar_am" name="cambiar_am" required value="<?php echo htmlspecialchars($perfil[0]->am); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" name="actualizar_nombre">Actualizar</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_nombre'])) 
                    {
                        $nuevo_nombre = $_POST['cambiar_nombre'];
                        $nuevo_ap = $_POST['cambiar_ap'];
                        $nuevo_am = $_POST['cambiar_am'];
                        $consulta = "call cambiar_nombre ('$user', '$nuevo_nombre', '$nuevo_ap', '$nuevo_am');";

                        $success = $conexion->seleccionar($consulta);

                        echo 
                        "
                            <script>
                                Swal.fire
                                ({
                                    title: 'Éxito',
                                    text: 'Datos personales actualizados con éxito.',
                                    icon: 'success'
                                }).then(function() 
                                {
                                    window.location.href = './perfil.php';
                                });
                            </script>
                        ";
                    }
                ?>

                <!-- Cambiar numero -->
                <h5 class="card-title">Cambiar número de teléfono</h5>
                <form action="" method="post" class="mb-3">
                    <div class="mb-3">
                        <input type="number" class="form-control" id="cambiar_telefono" name="cambiar_telefono" required value="<?php echo htmlspecialchars($perfil[0]->tel); ?>">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" name="actualizar_tel">Actualizar</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_tel'])) 
                    {
                        $nuevo_tel = $_POST['cambiar_telefono'];
                        $consulta = "call count_telefono ('$nuevo_tel')";

                        $count = $conexion->seleccionar($consulta);

                        if ($count[0]->count == 0)
                        {
                            $consulta = "call cambiar_telefono ('$user', '$nuevo_tel')";
                            $conexion->seleccionar($consulta);

                            echo 
                            '
                                <script>
                                    Swal.fire
                                    ({
                                        icon: "success",
                                        title: "Éxito",
                                        text: "Número de teléfono actualizado correctamente"
                                    }).then(function() 
                                    {
                                        window.location.href = "./perfil.php";
                                    });
                                </script>
                            ';
                        }
                        else
                        {
                            echo 
                            '
                                <script>
                                    Swal.fire
                                    ({
                                        icon: "error",
                                        title: "Error",
                                        text: "Error al actualizar el número de telefono"
                                    }).then(function() 
                                    {
                                        window.location.href = "./perfil.php";
                                    });
                                </script>
                            ';
                        }
                    }
                ?>


                <!-- Cambiar correo -->
                <h5 class="card-title">Correo electrónico</h5>
                <form action="" method="post" class="mb-3">
                    <div class="mb-3">
                        <input type="email" class="form-control" id="cambiar_correo" name="cambiar_correo" required value="<?php echo htmlspecialchars($perfil[0]->correo); ?>">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" name="actualizar_correo">Actualizar</button>
                    </div>
                </form>

                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_correo'])) 
                    {
                        $nuevo_correo = $_POST['cambiar_correo'];
                        $consulta = "call count_correo ('$nuevo_correo')";
                        $count = $conexion->seleccionar($consulta);

                        if ($count[0]->count == 0)
                        {
                            $consulta = "call cambiar_correo ('$user', '$nuevo_correo')";
                            $conexion->seleccionar($consulta);

                            echo 
                            '
                                <script>
                                    Swal.fire
                                    ({
                                        icon: "success",
                                        title: "Se cambió el correo",
                                        text: "Correo electrónico actualizado correctamente."
                                    }).then(function() 
                                    {
                                        window.location.href = "./perfil.php";
                                    });
                                </script>
                            ';
                        }
                        else
                        {
                            echo 
                            '
                                <script>
                                    Swal.fire
                                    ({
                                        icon: "error",
                                        title: "Error",
                                        text: "Error al actualizar el correo."
                                    }).then(function() 
                                    {
                                        window.location.href = "./perfil.php";
                                    });
                                </script>
                            ';
                        }
                    }
                ?>



                <!-- Cambiar Contraseña -->
                <h5 class="card-title">Cambiar contraseña</h5>
                <form action="" method="post" class="mb-3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label for="contrasena_0" class="form-label">Contraseña antigua</label>
                                <input type="password" class="form-control" id="contrasena_0" name="contrasena_0" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label for="contrasena_1" class="form-label">Nueva contraseña</label>
                                <input type="password" class="form-control" id="contrasena_1" name="contrasena_1" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label for="contrasena_2" class="form-label">Repetir contraseña</label>
                                <input type="password" class="form-control" id="contrasena_2" name="contrasena_2" required maxlength="16" minlength="8">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success" name="cambiar_contrasena">Cambiar contraseña</button>
                        </div>
                    </div>
                </form>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_contrasena'])) {
                    $contrasena_0 = $_POST['contrasena_0'];
                    $contrasena_1 = $_POST['contrasena_1'];
                    $contrasena_2 = $_POST['contrasena_2'];

                    $success = $conexion->verificar($user, $contrasena_0);

                    if ($success) 
                    {
                        if ($contrasena_1 === $contrasena_2) 
                        {
                            $nueva_contrasena = password_hash($contrasena_1, PASSWORD_DEFAULT);
                            $params = 
                            [
                                'p_user' => $user,
                                'p_nueva_contrasena' => $nueva_contrasena
                            ];
                            if ($conexion->ejecutarProcedimiento('cambiar_contrasena', $params)) 
                            {
                                echo 
                                '
                                    <script>
                                        Swal.fire
                                        ({
                                            title: "Éxito",
                                            text: "Contraseña cambiada con éxito.",
                                            icon: "success"
                                        }).then(function() 
                                        {
                                            window.location.href = "../../scripts/cerrar_sesion.php";
                                        });
                                    </script>
                                ';
                            } 
                            else 
                            {
                                echo 
                                '
                                    <script>
                                        Swal.fire
                                        ({
                                            title: "Error",
                                            text: "Error al cambiar la contraseña. Por favor, intenta de nuevo.",
                                            icon: "error"
                                        }).then(function() 
                                        {
                                            window.history.back();
                                        });
                                    </script>
                                ';
                            }
                        } 
                        else 
                        {
                            echo 
                            '
                                <script>
                                    Swal.fire({
                                        title: "Error",
                                        text: "Las nuevas contraseñas no coinciden.",
                                        icon: "error"
                                    }).then(function() {
                                        window.history.back();
                                    });
                                </script>
                            ';
                        }
                    } 
                    else 
                    {
                        echo 
                        '
                            <script>
                                Swal.fire({
                                    title: "Error",
                                    text: "Contraseña antigua incorrecta.",
                                    icon: "error"
                                }).then(function() {
                                    window.history.back();
                                });
                            </script>
                        ';
                    }
                }
                ?>

                <div class="alert alert-warning" role="alert">
                    Se cerrara la sesión al cambiar la contraseña.
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

