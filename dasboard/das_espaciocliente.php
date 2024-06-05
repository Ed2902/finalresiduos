<?php
require_once("../inventario/inventario.php");

// Crear una instancia de la clase Inventario
$inventario = new Inventario(null, null, null, null, null);

// Obtener los datos del dashboard
$datos_dashboard = $inventario->obtenerDatosDashboard();
$totales = $inventario->calcularTotalesProductos();

// Colores para cada producto en el gráfico
$colores = array(
    'rgba(255, 99, 132, 0.2)',
    'rgba(54, 162, 235, 0.2)',
    'rgba(255, 206, 86, 0.2)',
    'rgba(75, 192, 192, 0.2)',
    'rgba(153, 102, 255, 0.2)',
    'rgba(255, 159, 64, 0.2)'
);

// Metas de cada producto
$metas = array(500, 100, 0, 0, 0, 0); // Aquí debes reemplazar los valores con las metas reales de cada producto

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Inventario</title>
    <!-- Agregar estilos de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agregar estilos de DataTables -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Agregar Font Awesome para los íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px; /* Ajuste para el menú fijo */
        }
        .table-responsive {
            overflow-x: auto;
        }
        table#tablaConsolidadoProductos {
            width: 100% !important; /* Asegura que la tabla ocupe todo el ancho disponible */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard de Inventario</h1>

        <!-- Mostrar totales -->
        <div class="totales">
            <h2>Totales</h2>
            <p>Total Cantidad: <?php echo number_format($totales['total_cantidad'], 2, ',', '.'); ?> kg</p>
            <p>Total Valor: $<?php echo number_format($totales['total_valor'], 0, ',', '.'); ?></p>
        </div>

        <!-- Mostrar tabla consolidada -->
        <div class="tabla-consolidado">
            <h2>Consolidado de Productos</h2>
            <div class="table-responsive">
                <table id="tablaConsolidadoProductos" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID Producto</th>
                            <th>Nombre</th>
                            <th>Referencia</th>
                            <th>Tipo</th>
                            <th>Total Cantidad</th>
                            <th>Total Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $inventario->mostrarConsolidadoProductos();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mostrar gráfico -->
        <div class="grafico">
            <h2>Gráfico de Productos</h2>
            <canvas id="graficoProductos"></canvas>
        </div>
    </div>

    <!-- Agregar scripts de jQuery, DataTables y sus extensiones -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaConsolidadoProductos').DataTable();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('graficoProductos').getContext('2d');
            var datosDashboard = <?php echo json_encode($datos_dashboard); ?>;

            var nombres = datosDashboard.map(function(dato) {
                return dato.nombre_producto;
            });
            var pesos = datosDashboard.map(function(dato) {
                return dato.peso_total_producto;
            });

            var graficoProductos = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nombres,
                    datasets: [
                        {
                            label: 'Peso Total',
                            data: pesos,
                            backgroundColor: <?php echo json_encode($colores); ?>,
                            borderColor: <?php echo json_encode($colores); ?>,
                            borderWidth: 1
                        },
                        {
                            label: 'Meta',
                            data: <?php echo json_encode($metas); ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)', // Color para la meta
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
