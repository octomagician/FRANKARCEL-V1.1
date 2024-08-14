<?php
header('Content-Type: application/json;charset=utf-8');

include '../class/database.php';

$response = ['success' => false, 'message' => ''];

try 
{
    $conexion = new database();
    $conexion->conectarDB();

    if (!isset($_POST['usuario'], $_POST['nombre'], $_POST['apellido_paterno'], $_POST['apellido_materno'], $_POST['fecha_nacimiento'], $_POST['correo'], $_POST['telefono'], $_POST['contrasena'], $_POST['confirmar_contrasena'])) 
    {
        throw new Exception('Falta llenar algún campo.');
    }

    $contra1 = $_POST['contrasena'];
    $contra2 = $_POST['confirmar_contrasena'];

    if ($contra1 !== $contra2) 
    {
        throw new Exception('Las contraseñas no coinciden.');
    }

    $usuario = $_POST['usuario'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    // Validar nombre de usuario
    $consulta = "call buscar_nombre_usuario_cambio(:usuario)";
    $uso_usuario = $conexion->seleccionar($consulta, ['usuario' => $usuario]);

    // Validar teléfono
    $consulta = "call count_telefono(:telefono)";
    $uso_tel = $conexion->seleccionar($consulta, ['telefono' => $telefono]);

    // Validar correo
    $consulta = "call count_correo(:correo)";
    $uso_correo = $conexion->seleccionar($consulta, ['correo' => $correo]);

    if (empty($uso_usuario) || $uso_usuario[0]->count == 0)
    {
        if (empty($uso_tel) || $uso_tel[0]->count == 0 )
        {
            if (empty($uso_correo) || $uso_correo[0]->count == 0 )
            {
                $params = 
                [
                    'p_correo' => $correo,
                    'p_usuario' => $usuario,
                    'p_contrasena' => password_hash($_POST['contrasena'], PASSWORD_DEFAULT),
                    'p_nombre' => $_POST['nombre'],
                    'p_ap' => $_POST['apellido_paterno'],
                    'p_am' => $_POST['apellido_materno'],
                    'p_fecha_nac' => $_POST['fecha_nacimiento'],
                    'p_telefono' => $telefono
                ];

                if ($conexion->ejecutarProcedimiento('registro_usuario', $params)) 
                {
                    $response['success'] = true;
                    $response['message'] = 'Registro exitoso';
                    $response['redirect'] = 'http://35.94.6.94/index.php?showexampleModal=true';

                } 
                else 
                {
                    throw new Exception('Error en el registro. Por favor, intenta de nuevo.');
                }
            }
            else
            {
                $response['message'] = 'El correo ya está registrado. Inserte uno diferente.';
            }
        }
        else
        {
            $response['message'] = 'El número de télefono ya está registrado. Inserte uno diferente.';
        }
    } 
    else 
    {
        $response['message'] = 'El nombre de usuario ya está registrado. Inserte uno diferente.';
    }

} 
catch (PDOException $e) 
{
    if ($e->errorInfo[1] == 1062) 
    {
        $response['message'] = 'El correo, teléfono o usuario ya están registrados.';
    } 
    else 
    {
        $response['message'] = 'Error en el registro. ' . $e->getMessage();
    }
} 
catch (Exception $e) 
{
    $response['message'] = 'Error en el registro. ' . $e->getMessage();
}

echo json_encode($response);
?>