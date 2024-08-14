<?php
session_start();

$databasePath = realpath(dirname(__FILE__) . '/../class/database.php');
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

$permiso = false;
if ($roles) 
{
    foreach ($roles as $rol) 
    {
        if ($rol->rol == 'Administrador') 
        {
            $permiso = true;
            break;
        }
    }
}

if (!$permiso) 
{
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (isset($_POST['add_service'])) 
    {
        $tipo_servicio = $_POST['tipo_servicio'];
        $servicio = $_POST['servicio'];
        $descripcion = $_POST['descripcion'];

        $consulta = "call nuevo_servicio('$tipo_servicio', '$servicio', '$descripcion');";
        $conexion->seleccionar($consulta);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } 
    elseif (isset($_POST['update_service'])) 
    {
        $nombre_servicio = $_POST['servicio_modificar'];
        $nuevo_nombre = $_POST['servicio'];
        $descripcion = $_POST['descripcion'];

        $consulta = "call editar_servicio('$nombre_servicio', '$nuevo_nombre', '$descripcion');";
        $conexion->seleccionar($consulta);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <style>
        .sidebar {
            border-right: 2px solid #96e52e;
            width: 250px;
            background-color: #000;
            padding: 15px;
            color: #96e52e;
            height: 100vh;
            position: fixed;
            top: 0;
            left: -250px;
            transition: left 0.3s ease;
            z-index: 1000;
        }
        .sidebar a {
            color: #96e52e;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #96e52e;
            color: #000;
        }
        .sidebar .submenu {
            padding-left: 20px;
        }
        .main-content {
            padding: 20px;
        }
        .form-inline .btn {
            width: 175px;
        }
        .form-inline .form-control,
        .form-inline .form-select {
            flex: 1;
            margin-right: 10px;
        }
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
        .sidebar-open {
            left: 0;
        }
        .toggle-sidebar-btn {
            background-color: #7eb315;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        .hamburger-icon {
            display: inline-block;
            width: 20px;
            height: 2px;
            background-color: white;
            position: relative;
        }
        .hamburger-icon::before,
        .hamburger-icon::after {
            content: '';
            width: 20px;
            height: 2px;
            background-color: white;
            position: absolute;
            left: 0;
        }
        .hamburger-icon::before {
            top: -6px;
        }
        .hamburger-icon::after {
            bottom: -6px;
        }
        body {
            background-color: #242424;
        }
        h3{
            color: #96e52e;
        }
        p, h1, h2, h4, h6, label{
            color: #fff;
        }
        .custom-table thead {
            background-color: #96e52e;
            color: #000;
        }
        .custom-table th, .custom-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #fff;
        }
        .custom-table td{
            color: #fff;
        }
        .custom-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .chart-container {
            flex: 1;

            position: relative;
            height: 300px;
            width: 500px; 
            border: 3px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            padding: 10px; 
            background: #fff;
            border-radius: 8px;
            
        }
        .charts-row {
            display: flex;
            gap: 20px; 
                margin-top: 20px; 
        }
        .chart-container :hover {
            transition: 0.5s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6); 

        }
        .chart-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1; 

        }
        .chart-wrapper h2 {
            text-align: center;
        }

        .StatsContainerIngresoMes{
            border: 1px solid #ddd; 
            padding: 10px;
            border-radius: 5px; 
            background-color: #f9f9f9; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
            width: 200px;
        }
        .StatsContainerIngresoMes:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }

        .stats-container :hover{
            transition: 0.5s;
        }

        .stats-table th, .stats-table td 
        {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        
        .toggle-sidebar-btn
        { 
            opacity: 0.3;
        }

        .toggle-sidebar-btn:hover
        { 
            transition: 0.4s;
            opacity: 1;
        }
        
        .chart-container {
            width: 100%;
            height: auto;
        }

        </style>
</head>
<body>

    <button class="toggle-sidebar-btn" id="toggleSidebarBtn">
        <div class="hamburger-icon"></div>
    </button>

    <div class="sidebar" id="sidebar">
        <button class="toggle-sidebar-btn" id="hideSidebarBtn">
            <div class="hamburger-icon"></div>
        </button>
        <h1 style="margin-top: 35px;">Dashboard</h1>
        <a href="./administrador.php">Estadísticas</a>
        <div>
            <a href="#" class="menu-item" data-bs-toggle="collapse" data-bs-target="#productosMenu" aria-expanded="false" aria-controls="productosMenu">Productos ▼</a>
            <div class="collapse submenu" id="productosMenu">
                <a href="./admin_gestion_productos.php">Inventario y gestión</a>
                <a href="./admin_control_ventas.php">Control de ventas</a>
                <a href="./admin_reabastecimiento.php">Reabastecimiento</a>
                <a href="./admin_control_imagen.php">Gestion de imagenes</a>
            </div>
        </div>
        <div>
            <a href="#" class="menu-item" data-bs-toggle="collapse" data-bs-target="#serviciosMenu" aria-expanded="false" aria-controls="serviciosMenu">Servicios ▼</a>
            <div class="collapse submenu" id="serviciosMenu">
                <a href="./admin_gestion_servicios.php">Gestión</a>
                <a href="./admin_ajustes_servicio.php">Ajustes a servicio</a>
            </div>
        </div>
        <a href="./admin_gestion_usuario.php">Usuarios</a>
        <a href="./admin_notificaciones.php">Notificaciones</a>
        <a href="../index.php" class="" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); width: 80%;">Volver al inicio</a>
    </div>

    <script>
        const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
        const hideSidebarBtn = document.getElementById('hideSidebarBtn');
        const sidebar = document.getElementById('sidebar');

        toggleSidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-open');
        });

        hideSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('sidebar-open');
        });
    </script>

    <div class="main-content" style="margin-top: 35px;">
        <!-- aqui empiezan las graficas con CHART JS-->
        <div class="container mb-3 mt-3">
            <div class="row mb-3">
                <div class="col-lg-6 col-sm-12 mb-3 mt-3">
                    <div class="chart-wrapper">
                        <h2>Estadísticas de Ventas</h2>
                        <div class="chart-container">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 d-flex d-lg-none  mb-3 mt-3">
                    <div class="chart-wrapper">
                        <h2>Estadísticas de Servicios</h2>
                        <div class="chart-container">
                            <canvas id="myChart1"></canvas>
                        </div>
                    </div>
                </div>

                
            


                <?php
                    $consultaMes = 
                    "SELECT SUM(precio) AS total_mes 
                    FROM ventas 
                    WHERE DATE_FORMAT(fecha, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                    AND estado = 'Completado'";
                    $consulta3Meses = "SELECT SUM(precio) AS total_3_meses 
                        FROM ventas 
                        WHERE fecha >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
                        AND estado = 'Completado'";
                    $consulta6Meses = "SELECT SUM(precio) AS total_6_meses 
                        FROM ventas 
                        WHERE fecha >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                        AND estado = 'Completado'";
                    $consulta12Meses = "SELECT SUM(precio) AS total_12_meses 
                        FROM ventas 
                        WHERE fecha >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                        AND estado = 'Completado'";

                    $dataMes = $conexion->seleccionar($consultaMes);
                    $data3Meses = $conexion->seleccionar($consulta3Meses);
                    $data6Meses = $conexion->seleccionar($consulta6Meses);
                    $data12Meses = $conexion->seleccionar($consulta12Meses);
                    $totalMes = $dataMes[0]->total_mes ?? 0;
                    $total3Meses = $data3Meses[0]->total_3_meses ?? 0;
                    $total6Meses = $data6Meses[0]->total_6_meses ?? 0;
                    $total12Meses = $data12Meses[0]->total_12_meses ?? 0;



                    $consultaTopProductos = "
                SELECT p.nombre_producto, SUM(tv.cantidad) AS total_vendido
                FROM ticket_venta tv
                JOIN productos p ON tv.producto = p.id_producto
                GROUP BY p.id_producto, p.nombre_producto
                ORDER BY total_vendido DESC
                LIMIT 10";
                $dataTopProductos = $conexion->seleccionar($consultaTopProductos);

                $conexion->desconectarDB();
                ?>
                <div class="col-lg-6 col-sm-12 mb-3 mt-3">
                    <div class="top-products">
                        <h2>Top 10 Productos Más Vendidos</h2>
                        <div class="table_wrapper">
                            <table class="table table-striped table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th style="width: 70%;">Producto</th>
                                        <th style="width: 30%;">Total Vendido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataTopProductos as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($producto->nombre_producto); ?></td>
                                        <td><?php echo htmlspecialchars($producto->total_vendido); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-none d-sm-block mb-3 mt-3">
                    <div class="chart-wrapper">
                        <h2>Estadísticas de Servicios</h2>
                        <div class="chart-container">
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stats-container">
                <div class="row text-center">
                    <div class="col-lg-3 col-sm-12 mb-3 d-flex justify-content-center">
                        <div class="StatsContainerIngresoMes" style="height: 100px; width: 90%;">
                            <h5>Total Recaudado Este Mes: <span id="totalMes">0</span></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 mb-3 d-flex justify-content-center">
                        <div class="StatsContainerIngresoMes" style="height: 100px; width: 90%;">
                            <h5>Total Recaudado en Últimos 3 Meses: <span id="total3Meses">0</span></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 mb-3 d-flex justify-content-center">
                        <div class="StatsContainerIngresoMes" style="height: 100px; width: 90%;">
                            <h5>Total Recaudado en Últimos 6 Meses: <span id="total6Meses">0</span></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 mb-3 d-flex justify-content-center">
                        <div class="StatsContainerIngresoMes" style="height: 100px; width: 90%;">
                            <h5>Total Recaudado en Últimos 12 Meses: <span id="total12Meses">0</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
    var ctx = document.getElementById('myChart').getContext('2d');

    async function fetchData() {
    try {
        let response = await fetch('../scripts/obtenerdatosgrafico.php');
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.statusText);
        }
        let jsonData = await response.json();
        
        console.log('Datos obtenidos:', jsonData);


        let labels = jsonData.map(item => item.mes);

        let data = jsonData.map(item => item.ventas);


        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{

                    label: 'Ventas por mes',

                    data: data,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1


                }]
            },
            options: {
                scales: {


                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error al obtener los datos:', error);
    }
}

fetchData(); 

    </script>
<!-- para servicios-->
<script>
    var ctx2 = document.getElementById('myChart2').getContext('2d');

    async function fetchData2() {
        try {
            let response = await fetch('../scripts/obtenerdatosgraficoServicios.php');
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }
            let jsonData = await response.json();
            
            console.log('Datos obtenidos:', jsonData);

            let labels = jsonData.map(item => item.mes);
            let data = jsonData.map(item => item.Servicios_completados);

            var myChart2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Servicios por mes',
                        data: data,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    }

    fetchData2(); 
</script>


<script>
    var ctx2 = document.getElementById('myChart2').getContext('2d');

    async function fetchData2() {
        try {
            let response = await fetch('../scripts/obtenerdatosgraficoServicios.php');
            if (!response.ok) {
                throw new Error('Error en la solicitud: ' + response.statusText);
            }
            let jsonData = await response.json();
            
            console.log('Datos obtenidos:', jsonData);

            let labels = jsonData.map(item => item.mes);
            let data = jsonData.map(item => item.ventas);

            var myChart2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Servicios por mes',
                        data: data,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    }

    fetchData2(); 
</script>

<script>
        function animateNumber(elementId, targetNumber, duration) {
            const element = document.getElementById(elementId);
            const startNumber = 0;
            const stepTime = Math.abs(Math.floor(duration / targetNumber));
            let currentNumber = startNumber;

          
            const interval = setInterval(() => {
                currentNumber += 40;
                element.textContent = currentNumber.toFixed(2);

                if (currentNumber >= targetNumber) {
                    clearInterval(interval);
                    element.textContent = targetNumber.toFixed(2); 
                }
            }, stepTime);
        }

        const totalMes = <?php echo $totalMes; ?>;
        const total3Meses = <?php echo $total3Meses; ?>;
        const total6Meses = <?php echo $total6Meses; ?>;
        const total12Meses = <?php echo $total12Meses; ?>;

        document.addEventListener('DOMContentLoaded', () => {
            animateNumber('totalMes', totalMes, 1); 
            animateNumber('total3Meses', total3Meses, 1);
            animateNumber('total6Meses', total6Meses, 1);
            animateNumber('total12Meses', total12Meses, 1);
        });
    </script>

</body>
</html>