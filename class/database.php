<?php

class database
{
    private $PDOlocal;

    private $user = 'root';
    private $password = 'klo1926710';
    private $server = "mysql:host=localhost;dbname=frankarcell;";

    private $error;

   /* visto en clase de garay */
    public function conectarDB()
    {
        try {
            $this->PDOlocal = 
            new PDO($this->server, $this->user, $this->password);

            $this->PDOlocal->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

            $this->error = $e->getMessage();
            echo "Error de conexión: " . $this->error;
            die();
        }
    }

    public function desconectarDB()
    {
        $this->PDOlocal = null;
    }

    public function ejecutarProcedimiento($procedimiento, $params)
    {
        try {
            /* nos la complicamos omg*/
            $this->conectarDB();

            $paramString = 
            implode(', ', array_map(
                function($param) {
                return ":$param";

            }, array_keys($params)));
        
            $query = "CALL $procedimiento($paramString)";
            $stmt = $this->PDOlocal->prepare($query);
        
            foreach ($params as $key => $value) 
            {
                $stmt->bindValue(":$key", $value);
            }
        
            $stmt->execute();

            $this->desconectarDB();
        /* falta ver mejor esto */
            return ["status" => "success", "message" => "Operación exitosa"];

        } catch (PDOException $e) {

            
            $this->error = $e->getMessage();

            $this->desconectarDB();

            return ["status" => "error", "message" => $this->error];
        }
    }

    public function getError()
    {
        return $this->error;
    }

    public function seleccionar($consulta, $params = [])
    {
        try {
            $this->conectarDB();

            $stmt = $this->PDOlocal->prepare($consulta);

            foreach ($params as $key => $value) 
            {
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();

            $fila = $stmt->fetchAll(PDO::FETCH_OBJ);

            $this->desconectarDB();

            return $fila;

        } catch (PDOException $e)
         {
            $this->error = $e->getMessage();

            echo $this->error;

            $this->desconectarDB();
        }
    }

    public function verificar($usuario, $contra)
    {
        try {
            $this->conectarDB();
            $pase = false;
            
            $query = "SELECT * FROM usuario WHERE nombre_usuario = :usuario";

            $stmt = $this->PDOlocal->prepare($query);

            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

            $stmt->execute();
            /* mientras tenga datos dentro*/

            while ($renglon = $stmt->fetch(PDO::FETCH_ASSOC)) 
            {
                if (password_verify($contra, $renglon['contrasena'])) 
                {
                    $pase = true;
                }
            }
            /*siestru*/
            if ($pase)
             {
                session_start();

                $_SESSION["usuario"] = $usuario;
                
                $this->desconectarDB();

                return true;

            } else 
            {
                $this->desconectarDB();
                return false;
            }
        } catch (PDOException $e) 
        {
            $this->error = $e->getMessage();
            echo $this->error;

            $this->desconectarDB();

            return false;
        }
    }
        // porsi
        // no eliminar si lo usexdd
        public function prepare($sql)
         {

            return $this->PDOlocal->prepare($sql);
        }

        // nadamas para las paginas de los catalogos, si, no se me ocurrio otra cosa
        public function seleccionarparapaginas($consulta, $params = [])

        {
    try {
        $this->conectarDB();

        $stmt = $this->PDOlocal->prepare($consulta);

        foreach ($params as $index => $value) 
        {
            $stmt->bindValue($index + 1, $value, PDO::PARAM_INT);
        }

        $stmt->execute();

        $fila = $stmt->fetchAll(PDO::FETCH_OBJ);


        $this->desconectarDB();
        return $fila;
    } catch (PDOException $e)
    {
        $this->error = $e->getMessage();
        echo $this->error;
        $this->desconectarDB();
    }
}

    public function cerrarSesion()
    {
        session_start();
        session_destroy();
        header("Location: ../index.php");
    }
}
?>
