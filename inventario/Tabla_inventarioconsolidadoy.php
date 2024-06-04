<?php
    // Crear instancia de la clase Inventario
    require_once("./inventario.php");
    $inventario = new Inventario(null, null, null, null, null);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Fast Way</title>
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
        
        table#tablaInventario {
            width: 100% !important; /* Asegura que la tabla ocupe todo el ancho disponible */
        }
        
        #tablaInventario th,
        #tablaInventario td {
            text-align: center; /* Centrar el texto en todas las celdas */
        }
        
        #tablaInventario th:first-child,
        #tablaInventario td:first-child {
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
        <h1 class="mt-5 mb-3">Mi inventario General</h1>
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
        <table id="tablaInventario" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre Producto</th>
                    <th>Referencia</th>
                    <th>Tipo</th>
                    <th>Total Cantidad En kilos</th>
                    <th>Total Valor Pagado</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $inventario = new Inventario(null, null, null, null, null);
                $productos = $inventario->mostrarConsolidadoProductos();

                foreach ($productos as $producto) {
                    echo "<tr>";
                    echo "<td>" . $producto['id_producto'] . "</td>";
                    echo "<td>" . $producto['nombre'] . "</td>";
                    echo "<td>" . $producto['referencia'] . "</td>";
                    echo "<td>" . $producto['tipo'] . "</td>";
                    echo "<td>" . number_format($producto['total_cantidad'], 2, ',', '.') . "</td>";
                    echo "<td>$" . number_format($producto['total_valor'], 2, ',', '.') . "</td>";
                    echo "<td><button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#detallesModal' data-detalles='" . htmlspecialchars(json_encode($producto)) . "'>Detalles</button></td>";
                    echo "</tr>";
                }
            ?>
</tbody>
        </table>
    </div>
        <!-- Botón de Agregar -->
        <a href="../formularios/ingresar_inventario.php" class="btn btn-success mt-3"><i class="fas fa-plus"></i> Agregar</a>
    </div>
    <div class="modal fade" id="detallesModal" tabindex="-1" role="dialog" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesModalLabel">Detalles de Ingreso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se mostrarán los detalles de ingreso -->
                    <div id="detallesIngreso"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Agregar scripts de DataTables y Bootstrap al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            $('#tablaInventario').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"
                }
            });

            // Agregar la funcionalidad de filtro por fecha
            $('#filtroFecha').on('keyup change', function() {
                var fecha = $('#filtroFecha').val();
                $('#tablaInventario').DataTable().column(1).search(fecha).draw();
            });

            // Mostrar los detalles de ingreso en el modal
            $('#detallesModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var detalles = button.data('detalles');
                var detallesIngreso = JSON.parse(detalles);

                var html = '<table class="table table-striped table-bordered mt-3">';
                html += '<thead><tr><th>ID Ingreso</th><th>Fecha Hora</th><th>Usuario</th><th>Proveedor</th></tr></thead>';
                html += '<tbody>';

                detallesIngreso.forEach(function(detalle) {
                    html += '<tr>';
                    html += '<td>' + detalle.id_ingreso + '</td>';
                    html += '<td>' + detalle.fecha_hora + '</td>';
                    html += '<td>' + detalle.usuario + '</td>';
                    html += '<td>' + detalle.proveedor + '</td>';
                    html += '</tr>';
                });

                html += '</tbody></table>';

                $(this).find('#detallesIngreso').html(html);
            });
        });
    </script>
    <!-- Script para manejar la descarga de PDF -->
    <script>
        document.getElementById('descargarPDF').addEventListener('click', function() {
            var tabla = document.getElementById('tablaInventario');
            var fecha = document.getElementById('filtroFecha').value;
            var filename = 'Inventario_' + (fecha ? fecha : 'Sin_Fecha') + '.pdf';
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
            var tabla = document.getElementById('tablaInventario');
            var html = tabla.outerHTML;
            var url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            var link = document.createElement('a');
            link.download = 'Inventario.xls';
            link.href = url;
            link.click();
        });
    </script>
</body>
</html>
