<?php
    session_start();
    include '../class/database.php';
    $conexion = new database();
    $conexion->conectarDB();

    if ($_POST["dato"] == 'busca' AND $_POST["busqueda"] != '')
     {
        $key = explode(" ", $_POST["busqueda"]);
        $sql = "select * from productos where nombre_producto like '%" . $_POST["busqueda"] . "%'";

        for ($i = 0; $i < count($key); $i++)
         { 
            if (!empty($key[$i])) 
            {

                $sql .= " OR nombre_producto LIKE '%" . $key[$i] . "%'";
            }
        }

        $row_sql = $conexion->prepare($sql);
        $row_sql->execute();

        echo '<table class="col-12 m-0 p-0">
        <tbody>';


        while ($row = $row_sql->fetch(PDO::FETCH_ASSOC)) 
        {
                 // on mouse  over es basicamente un hover, no queria hacer un style para todo esto, no pregunten gente
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
            // <p class="card-text">' . htmlspecialchars($row["nombre_producto"]) . ' <br>' . htmlspecialchars($row["precio"]) . '</p>
        }

        echo '</tbody>
        </table>';
    }
?>
