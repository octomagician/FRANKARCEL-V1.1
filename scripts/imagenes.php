<?php
include '../class/database.php';

$conexion = new database();

$conexion->conectarDB();

$nombre_producto_completo = $_POST['celular'];
$nombre_producto = explode(':', $nombre_producto_completo)[0];
$nombre_producto = trim($nombre_producto); 
$padonde = $_POST['padonde'];


if($padonde == 'Carrusel')
{
    $producto = $conexion->seleccionar("SELECT id_producto FROM productos WHERE nombre_producto = :nombre", ['nombre' => $nombre_producto]);

    if ($producto && count($producto) > 0) 
    {
        $id_producto = $producto[0]->id_producto;
    
        $n_archivo = $_FILES['imagen']['name'];
        $archivo = $_FILES['imagen']['tmp_name'];
        $ruta = "../img/celulares/" . $n_archivo;
        $bd = "/img/celulares/" . $n_archivo;
    
        move_uploaded_file($archivo, $ruta);
    
        $params = 
        [
            'celular' => $id_producto,
            'bd' => $bd
        ];
    
        $resultado = $conexion->ejecutarProcedimiento("insertar_imagen", $params);
    
        if ($resultado['status'] == 'success') 
        {
            $response['status'] = 'success';
            $response['message'] = 'Imagen insertada correctamente en carrusel.';
        } 
        else 
        {
            $response['status'] = 'error';
            $response['message'] = 'Error al insertar la imagen: ' . htmlspecialchars($resultado['message'], ENT_QUOTES, 'UTF-8');
        }
    } 
    else 
    {
        $response['status'] = 'error';
        $response['message'] = 'Producto no encontrado.';
    }


}else if ($padonde == 'Principal')
{

    $producto = $conexion->seleccionar("SELECT id_producto FROM productos WHERE nombre_producto = :nombre", ['nombre' => $nombre_producto]);

    if ($producto && count($producto) > 0) 
    {
        $id_producto = $producto[0]->id_producto;
    
        $n_archivo = $_FILES['imagen']['name'];
        $archivo = $_FILES['imagen']['tmp_name'];
        $ruta = "../img/celulares/" . $n_archivo;
        $bd = "/img/celulares/" . $n_archivo;
    
        move_uploaded_file($archivo, $ruta);
    
        $params = 
        [
            'celular' => $id_producto,
            'bd' => $bd
        ];
    
        $resultado = $conexion->ejecutarProcedimiento("cambiar_imagen_productos", $params);
    
        if ($resultado['status'] == 'success') 
        {
            $response['status'] = 'success';
            $response['message'] = 'Imagen insertada correctamente en principal';

        } 
        else 
        {
            $response['status'] = 'error';
            $response['message'] = 'Error al insertar la imagen: ' . htmlspecialchars($resultado['message'], ENT_QUOTES, 'UTF-8');
        }
    } 
    else 
    {
        $response['status'] = 'error';
        $response['message'] = 'Producto no encontrado.';
    }


}
else if ($padonde =='Eliminar principal')
{

    $response = ['status' => 'error', 'message' => 'Error desconocido'];

        $producto = $conexion->seleccionar("SELECT id_producto FROM productos WHERE nombre_producto = :nombre", ['nombre' => $nombre_producto]);
        //siempre en una consulta se retorna un arry de objetos, aca abbajo se saca la id de la primera posicion de ese array
        $id_producto = $producto[0]->id_producto;
    
        $imagen = $conexion->seleccionar("SELECT img FROM productos WHERE id_producto = :id_producto", ['id_producto' => $id_producto]);
    
        if ($imagen && count($imagen) > 0) {
            $ruta_imagen = $imagen[0]->img;
    
            if (file_exists(".." . $ruta_imagen)) {
                unlink(".." . $ruta_imagen);
                $resultado = $conexion->seleccionar("UPDATE productos SET img = NULL WHERE id_producto = :id_producto", ['id_producto' => $id_producto]);
    
                $response['status'] = 'success';
                $response['message'] = 'Imagen eliminada correctamente.';
            } else {
                $response['message'] = 'No se pudo eliminar el archivo de imagen.';
            }
        }
        else 
        {
            $response['status'] = 'error';
            $response['message'] = 'Producto no encontrado.';
        }

    

 

}

else if ($padonde =='Eliminar carrusel')
{

    $response = ['status' => 'error', 'message' => 'Error desconocido'];

        $producto = $conexion->seleccionar("  SELECT p.id_producto FROM productos p JOIN imagenes_productos i ON p.id_producto = i.producto WHERE p.nombre_producto = :nombre", ['nombre' => $nombre_producto]);
        //siempre en una consulta se retorna un arry de objetos, aca abbajo se saca la id de la primera posicion de ese array
        $id_producto = $producto[0]->id_producto;
    
        $imagen = $conexion->seleccionar("SELECT url FROM imagenes_productos WHERE producto = :id_producto", ['id_producto' => $id_producto]);
    
        if ($imagen && count($imagen) > 0) {
            $ruta_imagen = $imagen[0]->url;
    
            if (file_exists(".." . $ruta_imagen)) {
                unlink(".." . $ruta_imagen);
                $resultado = $conexion->seleccionar("UPDATE imagenes_productos SET url = NULL WHERE producto = :id_producto", ['id_producto' => $id_producto]);
    
                $response['status'] = 'success';
                $response['message'] = 'Imagen eliminada correctamente del carrusel.';
            } else {
                $response['message'] = 'No se pudo eliminar el archivo de imagen.';
            }
        }
        else 
        {
            $response['status'] = 'error';
            $response['message'] = 'Producto no encontrado.';
        }

    

 

}



$conexion->desconectarDB();
echo json_encode($response);

?>