<?php
header('Content-Type: text/html; charset=utf-8');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .registration-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .registration-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #28a745;
            border: none;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control {
            box-shadow: none;
        }
        .form-text {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="registration-container">
        <h1 class="registration-title">Registrarse</h1>
        <form id="registroForm" action="../../scripts/registro_usuario.php" method="POST">
            <div class="mb-3">
                 <!-- acentos -->
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" placeholder="Nombre de usuario" minlength="3" maxlength="128">
                </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" placeholder="Nombre">
                </div>
            <div class="mb-3">
                <label for="apellido_paterno" class="form-label">Apellido paterno</label>
                <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" placeholder="Apellido paterno">
                </div>
            <div class="mb-3">
                <label for="apellido_materno" class="form-label">Apellido materno</label>
                <input type="text" name="apellido_materno" id="apellido_materno" class="form-control" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" placeholder="Apellido materno">
                </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
            </div>

            <script>
                const today = new Date();
                const minYear = today.getFullYear() - 16;
                const minDate = new Date(minYear, today.getMonth(), today.getDate());
                const minDateString = minDate.toISOString().split('T')[0];
                document.getElementById('fecha_nacimiento').setAttribute('max', minDateString);
            </script>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" class="form-control" required placeholder="Correo electrónico">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" class="form-control" required pattern="\d{10}" minlength="10" maxlength="10" placeholder="Número de teléfono">
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required minlength="8" placeholder="Contraseña" maxlength="512">
            </div>
            <div class="mb-3">
                <label for="confirmar_contrasena" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" class="form-control" required minlength="8" placeholder="Confirmar Contraseña" maxlength="512">
            </div>
            <div class="d-grid">
                <input type="submit" name="reg" id="reg" class="btn btn-success btn-custom" value="Registrarse">
            </div>
        </form>
        <div class="form-text">
            <p>¿Ya tienes cuenta? <a href="../../index.php">Volver al inicio</a></p>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
       document.getElementById('registroForm').addEventListener('submit', function(event) 
    {
    event.preventDefault();
    let formData = new FormData(this);
    fetch(this.action, 
    {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => 
    {
        if (data.success) 
        {
            Swal.fire
            ({
                title: 'Registro Exitoso',
                text: 'El usuario se ha registrado correctamente.',
                icon: 'success'
            }).then(() => 
            {
                window.location.href = data.redirect;
            });
        } 
        else 
        {
            Swal.fire
            ({
                title: 'Error en el Registro',
                text: data.message,
                icon: 'error'
            });
        }
    })
    .catch(error => 
    {
        console.error('Error:', error);
        Swal.fire
        ({
            title: 'Error en el Registro',
            text: 'Hubo un problema al procesar tu solicitud.',
            icon: 'error'
        });
    });
});

    </script>
</body>
</html>
