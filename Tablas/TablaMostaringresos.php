<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla</title>
    <!-- Incluir archivos CSS de Bootstrap y DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Incluir Font Awesome para los íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Estilos adicionales -->
    <style>
        body {
            padding-top: 20px; /* Ajuste para el menú fijo */
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        #miTabla th,
        #miTabla td {
            text-align: center; /* Centrar el texto en todas las celdas */
        }
        
        #miTabla th:first-child,
        #miTabla td:first-child {
            font-weight: bold; /* Hace que el texto en la primera columna (ID Ingreso) sea negrita */
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../Home/home.html">
            <i class="fas fa-home" style="font-size: 20px; color:#fe5000;"></i>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="../Home/home.html">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../formularios/ingresar_inventario.php">Agregar</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Tabla -->
    <div class="container-fluid" style="width: 90%;">
        <table id="miTabla" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID Ingreso</th>
                    <th>Fecha de Ingreso</th>
                    <th>ID Usuario</th>
                    <th>ID Cliente</th>
                    <th>Acciones</th> <!-- Columna para botones -->
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se mostrarán los datos de la tabla ingresos -->
                <?php
                    require_once '../clases/Inventario.php';

                    // Crear una instancia de la clase Inventario
                    $inventario = new Inventario(null, null);

                    // Obtener los datos de la tabla ingresos
                    $datos_ingresos = $inventario->obtenerTodosLosIngresos(); 

                    // Iterar sobre los datos e imprimir cada fila
                    foreach ($datos_ingresos as $dato) {
                        echo "<tr>";
                        echo "<td>{$dato['id_ingreso']}</td>";
                        echo "<td>{$dato['fecha']}</td>";
                        echo "<td>{$dato['id_usuarioFK']}</td>";
                        echo "<td>{$dato['id_clienteFK']}</td>";
                        // Agregar botones e iconos
                        echo "<td>";
                        echo "<button class='ver-datos btn btn-primary' data-id='{$dato['id_ingreso']}'><i class='fas fa-eye'></i> Ver Detalles</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>      
            </tbody>
        </table>
    </div>

    <div id="datos"></div>

    <!-- Incluir scripts de jQuery, DataTables y Bootstrap al final del cuerpo del documento -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function(){
            // Inicializar DataTables en la tabla
            var tabla = $('#miTabla').DataTable();

            // Delegación de eventos para clic en filas
            $('#miTabla tbody').on('click', 'tr', function () {
                var data = tabla.row(this).data();
                if(data){
                    // Obtener el ID de ingreso de la fila clicada
                    var id = data[0]; // Suponiendo que el ID de ingreso está en la primera columna
                    // Realizar la llamada AJAX y mostrar los datos correspondientes
                    $.ajax({
                        url: "../objetos_guardar/obtener_datos_ingreso.php",
                        method: "POST",
                        data: { id: id },
                        success: function(response){
                            $("#datos").html(response);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
