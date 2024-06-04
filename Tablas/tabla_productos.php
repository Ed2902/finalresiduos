<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Ingresos</title>
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
        table#tablaIngresos {
            width: 100% !important; /* Asegura que la tabla ocupe todo el ancho disponible */
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="width: 90%;">
        <!-- Título -->
        <h1 class="mt-5 mb-3">Tabla de Ingresos</h1>
        <div class="table-responsive">
            <table id="tablaIngresos" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Ingreso</th>
                        <th>Fecha de Ingreso</th>
                        <th>ID Usuario</th>
                        <th>ID Cliente</th>
                    </tr>
                </thead>
                <tbody>
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
                            echo "</tr>";
                        }
                    ?>      
                </tbody>
            </table>
        </div>
    </div>

    <!-- Agregar scripts de DataTables y Bootstrap al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaIngresos').DataTable({
                "searching": true 
            });
        });
    </script>
</body>
</html>
