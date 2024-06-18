<?php
// Incluir el archivo que contiene la definición de la clase SalidaInventario
require_once("./SalidaInventario.php");

// Crear una instancia de la clase SalidaInventario
$salidaInventario = new SalidaInventario(null, null);

// Obtener todos los registros de la tabla salida
$salidas = $salidaInventario->consultarSalidas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Salidas</title>
    <!-- Agregar estilos de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agregar estilos de DataTables -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Agregar Font Awesome para los íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Estilos adicionales -->
    <style>
        body {
            padding-top: 20px; /* Ajuste para el menú fijo */
        }

        .table-responsive {
            overflow-x: auto;
        }

        table#tablaSalidas {
            width: 100% !important; /* Asegura que la tabla ocupe todo el ancho disponible */
        }

        #tablaSalidas th,
        #tablaSalidas td {
            text-align: center; /* Centrar el texto en todas las celdas */
        }

        #tablaSalidas th:first-child,
        #tablaSalidas td:first-child {
            font-weight: bold; /* Hace que el texto en la primera columna (ID Producto) sea negrita */
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="width: 90%;">
        <!-- Botón de Casa -->
        <a href="../Home/home.html" style="text-decoration: none;">
            <button type="button" class="btn btn-light mr-2" style="border-radius: 50%;">
                <i class="fas fa-home" style="font-size: 20px; color:#fe5000;"></i>
            </button>
        </a>
        <h1 class="mt-5 mb-3">Inventario de Salidas</h1>
        <!-- Campo de búsqueda por fecha -->
        <div class="form-group row justify-content-end">
            <label for="filtroFecha" class="col-md-2 col-form-label text-right">Buscar por Fecha:</label>
            <div class="col-md-3">
                <input type="text" class="form-control" id="filtroFecha" placeholder="YYYY-MM-DD">
            </div>
            <!-- Icono de Descargar PDF -->
            <i id="descargarPDF" class="fas fa-file-pdf" style="color: #74C0FC; font-size: 30px; padding:2px"></i>

            <!-- Icono de Descargar Excel -->
            <i id="descargarExcel" class="fas fa-file-excel" style="font-size: 30px; color:#fe5000; padding:2px; margin-right:11px"></i>
        </div>

        <div class="table-responsive">
            <table id="tablaSalidas" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Suma Total Kilos</th>
                        <th>Fecha y Hora</th>
                        <th>Valor Total</th>
                        <th>Evidencias</th>
                        <th>Documentación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($salidas as $salida): ?>
                        <tr>
                            <td><?php echo $salida['id']; ?></td>
                            <td><?php echo number_format($salida['suma_total_kilos'], 0, ',', '.'); ?></td>
                            <td><?php echo $salida['fecha_hora']; ?></td>
                            <td><?php echo '$' . number_format($salida['valor_total'], 2, ',', '.'); ?></td>
                            <td><a href="<?php echo $salida['evidencias']; ?>" download>Descargar</a></td>
                            <td><a href="<?php echo $salida['documentacion']; ?>" download>Descargar</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Botón de Agregar -->
        <a href="./ingresar_inventario.php" class="btn btn-success mt-3"><i class="fas fa-plus"></i> Agregar</a>
    </div>

    <!-- Agregar scripts de DataTables y Bootstrap al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            $('#tablaSalidas').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"
                }
            });

            // Agregar la funcionalidad de filtro por fecha
            $('#filtroFecha').on('keyup change', function() {
                var fecha = $('#filtroFecha').val();
                $('#tablaSalidas').DataTable().column(2).search(fecha).draw();
            });
        });
    </script>
    <!-- Script para manejar la descarga de PDF -->
    <script>
        document.getElementById('descargarPDF').addEventListener('click', function() {
            var tabla = document.getElementById('tablaSalidas');
            var fecha = document.getElementById('filtroFecha').value;
            var filename = 'Salidas_' + (fecha ? fecha : 'Sin_Fecha') + '.pdf';
            html2pdf(tabla, {
                margin: 1,
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
            });
        });
    </script>

    <!-- Script para manejar la descarga de Excel -->
    <script>
        document.getElementById('descargarExcel').addEventListener('click', function() {
            var tabla = document.getElementById('tablaSalidas');
            var html = tabla.outerHTML;
            var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            var link = document.createElement('a');
            link.download = 'Salidas.xls';
            link.href = url;
            link.click();
        });
    </script>
</body>
</html>
