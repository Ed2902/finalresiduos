<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Espacio Ocupado por Cliente</title>
    <!-- Importa la fuente HemiHeadRg-BoldItalic desde Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=HemiHeadRg:wght@700&family=HemiHeadRg&display=swap">
    <!-- Incluye las librerías de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Incluye Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Estilos opcionales para el contenedor del gráfico */
        body {
            font-family: 'HemiHeadRg-BoldItalic', sans-serif;
        }
        h1, h2 {
            text-align: center;
        }
        #chart-container {
            width: 100%;
            max-width: 800px; 
            margin: 20px auto;
        }
        #espacio-libre {
            background-color: #F74C1B;
            height: 20px;
            width: 100%;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        #espacio-libre-barra {
            background-color: #59A1F7;
            height: 100%;
            width: 0%; /* Comienza con 0% de ancho */
            position: absolute;
            top: 0;
            left: 0;
            animation: loading 2s linear 1; /* Animación de 2 segundos que se ejecuta una vez */
        }
        #espacio-libre-numero {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
            color: black;
        }
        .espacio-libre-contenedor {
            width: 100%;
            max-width: 60%; /* Cambia el ancho máximo aquí */
            margin: 0 auto; /* Centra horizontalmente */
        }
        /* Media queries para dispositivos móviles */
        @media screen and (max-width: 768px) {
            #chart-container {
                max-width: 90%; /* Ancho máximo del contenedor para dispositivos móviles */
            }
            .espacio-libre-contenedor {
                max-width: 90%; /* Ancho máximo del contenedor para dispositivos móviles */
            }
        }
        /* Estilos para la animación de carga */
        @keyframes loading {
            0% { width: 0%; }
            100% { width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Botón de Casa -->
    <div class="container-fluid" style="width: 90%;">
    <a href="../Home/home.html" style="text-decoration: none; display: inline-block; background-color: #fff; width: 40px; height: 40px; border-radius: 50%; text-align: center; line-height: 40px;">
    <i class="fas fa-home" style="font-size: 20px; color:#fe5000; margin-top: 25px;"></i>
    </a>
    </div>

    <h1>Espacio Ocupado en bodega por Cliente</h1>
    <div id="chart-container">
        <canvas id="espacio-cliente-chart"></canvas>
    </div>
    <h2>Espacio Libre</h2>
    <div class="espacio-libre-contenedor">
        <div id="espacio-libre">
            <div id="espacio-libre-barra"></div>
            <div id="espacio-libre-numero"></div>
        </div>
    </div>

    <?php
    // Incluye la clase Inventario
    require_once '../clases/inventario.php';

    // Crea una instancia de Inventario
    $objInventario = new Inventario(null, null, null, null, null);

    // Llama al método calcularEspacioCliente para obtener los datos
    $datos_por_cliente = $objInventario->calcularEspacioCliente();

    // Calcula el espacio total ocupado y el espacio libre
    $espacio_total_ocupado = array_sum($datos_por_cliente);
    $espacio_libre = 1000 - $espacio_total_ocupado; // 1000 es el espacio total disponible

    // Definir la paleta de colores y los colores de borde
    $colores_barras = array("#59A1F7", "#F74C1B", "#F86B25", "#FF1403", "#00E925", "#282828", "#FFFFFF");
    $colores_borde = array("#000000", "#000000", "#000000", "#000000", "#000000", "#000000");

    // Convierte los datos en formato JSON para pasarlos al script de JavaScript
    $datos_json = json_encode($datos_por_cliente);
    $colores_json = json_encode($colores_barras);
    $bordes_json = json_encode($colores_borde);
    ?>

    <script>
        // Recibe los datos JSON del PHP
        var datosPorCliente = <?php echo $datos_json; ?>;
        var coloresBarras = <?php echo $colores_json; ?>;
        var coloresBorde = <?php echo $bordes_json; ?>;

        // Extrae las etiquetas (IDs de cliente) y los datos de espacio ocupado
        var etiquetas = Object.keys(datosPorCliente);
        var datos = Object.values(datosPorCliente);

        // Configura el gráfico
    var ctx = document.getElementById('espacio-cliente-chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiquetas.map(function(etiqueta, index) {
                return etiqueta + ' ' + datos[index].toFixed(2) + ' m³'; // Agrega "m³" después de cada dato
            }),
            datasets: [{
                label: 'Ocupado',
                data: datos,
                backgroundColor: coloresBarras, 
                borderColor: coloresBorde,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
        });
        // Función para actualizar la barra de espacio libre
        function actualizarEspacioLibre(espacioLibre) {
            var barra = document.getElementById('espacio-libre-barra');
            var porcentaje = (espacioLibre / 1000) * 100; // Calcula el porcentaje de espacio libre
            barra.style.width = porcentaje + '%';
            document.getElementById('espacio-libre-numero').textContent = espacioLibre.toFixed(2) + ' m³ libres'; // Redondea a 2 decimales
        }

        // Simulación de actualización del espacio libre (puedes reemplazar esto con tu lógica real)
        setTimeout(function() {
            // Aquí deberías obtener el espacio libre actualizado desde tu backend
            var espacioLibre = <?php echo $espacio_libre; ?>;
            actualizarEspacioLibre(espacioLibre);
        }, 2000); // Simula una carga de 2 segundos
    </script>
    <div style="text-align: center; margin-top: 20px;">
    <a href="../Tablas/Tabla_inventarioconsolidadoy.php" class="btn btn-success">Ver mi inventario</a>
    </div>
</body>
</html>
