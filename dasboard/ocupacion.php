<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Espacio Disponible</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container mt-5">
    <h1>Dashboard - Espacio Disponible</h1>

    <?php
    require_once "../Clases/inventario.php"; // Asegúrate de ajustar la ruta según la ubicación real de tu archivo inventario.php

    // Crear una instancia de Inventario
    $inventario = new Inventario(null, null, null, null, null);

    // Llamar al método calcularEspacioDisponible()
    $espacio_disponible = $inventario->calcularEspacioDisponible();

    // Espacio total de la bodega (en metros cúbicos)
    $espacio_total_bodega = 1000;

    // Espacio ocupado (calculado)
    $espacio_ocupado = $espacio_total_bodega - $espacio_disponible;

    // Etiquetas y datos para el gráfico
    $labels = ["Espacio Ocupado", "Espacio Disponible"];
    $data = [$espacio_ocupado, $espacio_disponible];

    ?>

    <canvas id="graficoEspacio" width="400" height="200"></canvas>

    <script>
        var ctx = document.getElementById('graficoEspacio').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Espacio en la Bodega',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', // Rojo para espacio ocupado
                        'rgba(54, 162, 235, 0.5)' // Azul para espacio disponible
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
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
    </script>
</div>

</body>
</html>
