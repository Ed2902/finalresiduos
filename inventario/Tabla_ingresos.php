<?php
    // Incluir la clase Ingreso
    require_once("./Ingreso.php");

    // Obtener todos los ingresos
    $ingresos = Ingreso::obtenerTodosLosIngresos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresos y Detalles</title>
    <!-- Agregar estilos de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid" style="width: 90%;">
        <h1 class="mt-5 mb-3">Ingresos</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Productos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ingresos as $ingreso): ?>
                        <tr>
                            <td><?= $ingreso['id'] ?></td>
                            <td><?= $ingreso['fecha_hora'] ?></td>
                            <td><a href="#" onclick="mostrarDetalles(<?= $ingreso['id'] ?>)">Ver Detalles</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Contenedor para mostrar detalles de ingreso -->
        <div id="detallesIngreso"></div>
    </div>

    <!-- Agregar script para mostrar detalles de ingreso -->
    <script>
        function mostrarDetalles(idIngreso) {
            // Realizar una petición AJAX para obtener los detalles del ingreso
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("detallesIngreso").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "detalles_ingreso.php?id=" + idIngreso, true);
            xhttp.send();
        }
    </script>
</body>
</html>
