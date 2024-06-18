<?php
include_once "../login/verificar_sesion.php"; 
require_once "../inventario/inventario.php"; 

// Crear una instancia de la clase Inventario
$inventario = new Inventario(null, null, null, null, null);

// Verificar si se ha enviado el ID del proveedor
if (isset($_POST['proveedor'])) {
    // Obtener el ID del proveedor seleccionado
    $id_proveedor = $_POST['proveedor'];
    // Obtener los productos asociados al proveedor seleccionado
    $productos = $inventario->obtenerProductosPorProveedor($id_proveedor);
    // Devolver los productos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($productos);
    exit(); // Salir del script después de enviar la respuesta JSON
}

// Si no se ha enviado el ID del proveedor, continuar con el resto del código

// Obtener los nombres de los proveedores
$nombres_proveedores = $inventario->obtenerNombresProveedores();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de productos por proveedor</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Estilos adicionales -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;  
        }
        h1 {
            text-align: center;
        }
        .proveedor-table {
            margin-top: 25px;
            width: 120%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-left: -60px; /* Desplazar hacia la izquierda */
        }
        .proveedor-table th, .proveedor-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center; /* Centrar texto */
            font-size: 14px;
        }
        .proveedor-table th {
            background-color: #f2f2f2;
            cursor: pointer; /* Mostrar que los encabezados son clicables */
        }
        .proveedor-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .chart-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .chart {
            margin-bottom: 20px;
        }
        .home-icon {
            font-size: 20px;
            color: #fe5000;
            padding: 0;
            margin-right: 5px;
        }
        #search {
            width: 200px; /* Ancho del cuadro de búsqueda */
            float: right; /* Mover a la derecha */
            margin-bottom: 10px; /* Espacio entre el cuadro de búsqueda y la tabla */
        }

        .titulo{
            margin-bottom: 40px;
        }
        
    </style>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <!-- Botón de Casa -->
        <a href="../Home/home.html" style="text-decoration: none;">
            <button type="button" class="btn btn-light mr-2" style="border-radius: 50%;">
                <i class="fas fa-home home-icon"></i>
            </button>
        </a>
        <h1 class="titulo">Productos por proveedor</h1>

        <!-- Cuadro de búsqueda -->
        <input type="text" id="search" placeholder="Buscar por nombre...">

        <form id="proveedorForm">
            <div class="form-group">
                <label for="proveedor">Seleccione un proveedor:</label>
                <select class="form-control" name="proveedor" id="proveedor">
                    <option value="">Seleccione un proveedor</option>
                    <?php foreach ($nombres_proveedores as $id_proveedor => $nombre_proveedor): ?>
                        <option value="<?php echo $id_proveedor; ?>"><?php echo $nombre_proveedor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="mostrarProductosBtn">Mostrar productos</button>
        </form>

        <div id="productosContainer"></div>

        <div class="chart-container">
            <div class="chart">
                <canvas id="chartValores" width="800" height="400"></canvas>
            </div>
            <div class="chart">
                <canvas id="chartPeso" width="800" height="400"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('proveedorForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario de forma tradicional

            var formData = new FormData(this); // Obtener los datos del formulario
            var xhr = new XMLHttpRequest(); // Crear una nueva solicitud AJAX

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText); // Convertir la respuesta a JSON
                        var productosHtml = '<table class="proveedor-table" border="1"><thead><tr><th onclick="sortTable(0)">ID Inventario</th><th onclick="sortTable(1)">ID Producto</th><th onclick="sortTable(2)">Nombre Producto</th><th onclick="sortTable(3)">Quién dio ingreso</th><th onclick="sortTable(4)">Peso (kg)</th><th onclick="sortTable(5)">Valor Por Kilo</th><th onclick="sortTable(6)">Valor Total</th><th onclick="sortTable(7)">Fecha y Hora de Ingreso</th></tr></thead><tbody>';

                        // Variables para calcular el total
                        var totalPeso = 0;
                        var totalValor = 0;
                        var totalValorPorKilo = 0;
                        var nombresProductos = [];
                        var pesos = [];
                        var valoresPorKilo = [];

                        // Construir la tabla de productos con los datos obtenidos
                        response.forEach(function(producto) {
                            var peso = parseFloat(producto.peso);
                            var valorPorKilo = parseFloat(producto.valorPorKilo);
                            var valorTotal = peso * valorPorKilo;

                            totalPeso += peso;
                            totalValor += valorTotal;
                            totalValorPorKilo += valorPorKilo;

                            nombresProductos.push(producto.nombre_producto);
                            pesos.push(peso);
                            valoresPorKilo.push(valorPorKilo);
                            productosHtml += '<tr><td>' + producto.id_inventario + '</td><td>' + producto.id_producto + '</td><td>' + producto.nombre_producto + '</td><td>' + producto.nombre_usuario + '</td><td>' + peso.toFixed(2) + ' kg</td><td>$' + valorPorKilo.toLocaleString('es-ES', {minimumFractionDigits: 0, maximumFractionDigits: 0}) + '</td><td>$' + valorTotal.toLocaleString('es-ES', {minimumFractionDigits: 0, maximumFractionDigits: 2}) + '</td><td>' + producto.fecha_hora + '</td></tr>';
                    });

                    productosHtml += '</tbody>';
                    productosHtml += '<tfoot><tr><td colspan="4"><strong>Total</strong></td><td><strong>' + totalPeso.toFixed(2) + ' kg</strong></td><td><strong>$' + totalValorPorKilo.toLocaleString('es-ES', {minimumFractionDigits: 0, maximumFractionDigits: 0}) + '</strong></td><td><strong>$' + totalValor.toLocaleString('es-ES', {minimumFractionDigits: 0, maximumFractionDigits: 2}) + '</strong></td><td></td></tr></tfoot>';
                    productosHtml += '</table>';
                    document.getElementById('productosContainer').innerHTML = productosHtml; // Actualizar el contenido del contenedor de productos

                    // Crear gráfico con Chart.js
                    var ctxValores = document.getElementById('chartValores').getContext('2d');
                    var chartValores = new Chart(ctxValores, {
                        type: 'bar',
                        data: {
                            labels: nombresProductos,
                            datasets: [{
                                label: 'Valores ($)',
                                data: valoresPorKilo,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
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

                    var ctxPeso = document.getElementById('chartPeso').getContext('2d');
                    var chartPeso = new Chart(ctxPeso, {
                        type: 'bar',
                        data: {
                            labels: nombresProductos,
                            datasets: [{
                                label: 'Peso (kg)',
                                data: pesos,
                                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                borderColor: 'rgba(255, 206, 86, 1)',
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

                    // Volver a agregar el evento de búsqueda
                    addSearchEvent();

                } else {
                    console.error('Hubo un error al obtener los productos');
                }
            }
        };

        xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', true); // Especificar el método y la URL del archivo PHP que procesará la solicitud
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Agregar encabezado para indicar que es una solicitud AJAX
        xhr.send(formData); // Enviar la solicitud con los datos del formulario
    });

    // Función para agregar el evento de búsqueda
    function addSearchEvent() {
        document.getElementById('search').addEventListener('input', function() {
            var input, filter, table, tr, td, i, txtValue;
            input = this.value.toLowerCase();
            table = document.querySelector('.proveedor-table');
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2]; // Cambiar el índice si quieres buscar en otra columna
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(input) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    }

    // Función para ordenar la tabla por una columna específica
    function sortTable(columnIndex) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.querySelector('.proveedor-table');
        switching = true;
        dir = "asc"; 
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 2); i++) { // Ajustar el límite de filas para omitir la fila de "Total"
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[columnIndex];
                y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                if (dir == "asc") {
                    if (columnIndex !== 4 && columnIndex !== 5 && columnIndex !== 6) {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                } else if (dir == "desc") {
                    if (columnIndex !== 4 && columnIndex !== 5 && columnIndex !== 6) {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else {
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

    // Agregar el evento de búsqueda al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        addSearchEvent();
    });
</script>
