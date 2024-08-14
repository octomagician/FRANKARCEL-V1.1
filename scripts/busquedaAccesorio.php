<?php
session_start();
include '../class/database.php';

try 
{
    $conexion = new database();
    $conexion->conectarDB();

    if ($_POST["dato"] == 'busca' && !empty($_POST["busqueda"])) 
    {
        $key = explode(" ", $_POST["busqueda"]);
        $sql = "select * from productos where categoria = 2 and (nombre_producto like :busqueda";

        foreach ($key as $i => $word) 
        {
            if (!empty($word)) 
            {
                $sql .= " OR nombre_producto LIKE :word$i";
            }
            
        }
        $sql .= ")";

        $stmt = $conexion->prepare($sql);

        $params = ['busqueda' => '%' . $_POST["busqueda"] . '%'];
        foreach ($key as $i => $word) 
        {
            if (!empty($word)) 
            {
                $params["word$i"] = '%' . $word . '%';
            }
        }

        $stmt->execute($params);

        echo '<table class="col-12 m-0 p-0">
        <tbody>';
        // copie y pege lo de catalogo, como no lo iba a hacer 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            echo '<tr>

            <td style="vertical-align:middle; text-align:left;">

                <button type="button" onclick="filtrar()" style="border: none; background: transparent; width: 100%; display: flex; align-items: center; padding: 10px; cursor: pointer; transition: background-color 0.3s, box-shadow 0.3s;"
                        onmouseover="this.style.backgroundColor=\'#f0f0f0\'; this.style.boxShadow=\'0px 4px 8px rgba(0, 0, 0, 0.2)\';"
                        onmouseout="this.style.backgroundColor=\'transparent\'; this.style.boxShadow=\'none\';">

                    <img src="../..' . htmlspecialchars($row["img"]) . '" width="50" height="65px" style="margin-right: 15px;">


                                <p class="card-text" style="margin: 0; flex: 1; color: black;">'  . htmlspecialchars($row["nombre_producto"]) . '</p>
                </button>
            </td>
            </tr>';

        }
        echo '</tbody>
        </table>';
    }
} 
catch (PDOException $e) 
{
    echo 'Error: ' . $e->getMessage();
}
?>
