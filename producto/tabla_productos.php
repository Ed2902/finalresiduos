<?php include_once "../login/verificar_sesion.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Productos</title>
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
        table#tablaProductos {
            width: 100% !important; /* Asegura que la tabla ocupe todo el ancho disponible */
        }
        .home-btn {
            position: absolute; /* Posiciona el botón de casa de forma absoluta */
            left: 80px; /* Alinea a la izquierda */
            top: 20px; /* Alinea arriba */
            border-radius: 50%; /* Estilo de botón de casa */
        }
        .add-btn {
            position: relative; /* Posiciona el botón de agregar de forma fija */
            bottom: 30%; /* Alinea abajo */
            left: 60px; /* Alinea a la izquierda */
            color: #fff; /* Color del texto del botón de agregar */
            margin-left: 5px;
        }
        /* Estilo para el botón de Excel */
        .excel-btn {
            background-color: #fe5000; /* Color de fondo */
            border-color: #fe5000; /* Color del borde */
            color: #fff; /* Color del texto */
            float: right; /* Alinea el botón a la derecha */
            margin-left: 10px; /* Espacio a la izquierda del botón para separarlo de otros elementos */
        }

        /* Estilo para el icono de Excel */
        .excel-icon {
            color: #fff; /* Color del icono */
        }

        /* Estilo para el botón de PDF */
        .pdf-btn {
            background-color: #74C0FC; /* Color de fondo */
            border-color: #74C0FC; /* Color del borde */
            color: #fff; /* Color del texto */
            float: right; /* Alinea el botón a la derecha */
            margin-left: 10px
        }

        /* Estilo para el icono de PDF */
        .pdf-icon {
            color: #fff; /* Color del icono */
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="width: 90%;">
        <!-- Botón de Casa -->
        <a href="../Home/home.html" style="text-decoration: none;">
            <button type="button" class="btn btn-light home-btn">
                <i class="fas fa-home" style="font-size: 20px; color:#fe5000;"></i>
            </button>
        </a>
        <!-- Título -->
        <h1 class="mt-5 mb-3">Tabla de Productos</h1>
        <div class="table-responsive">
            <table id="tablaProductos" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Referencia</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>ID Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        require_once '../conexion.php';
                        require_once './Producto.php';

                        // Obtener todos los productos
                        $productos = Producto::obtenerTodosLosProductos();

                        // Iterar sobre los datos e imprimir cada fila
                        foreach ($productos as $dato) {
                            echo "<tr>";
                            echo "<td>{$dato['id_producto']}</td>";
                            echo "<td>{$dato['nombre']}</td>";
                            echo "<td>{$dato['referencia']}</td>";
                            echo "<td>{$dato['tipo']}</td>";
                            echo "<td>{$dato['fecha']}</td>";
                            echo "<td>{$dato['id_usuarioFK']}</td>";
                            echo "</tr>";
                        }
                    ?>   
                </tbody>
            </table>
        </div>
        <!-- Botón de Agregar -->
        <a href="./producto.html" class="btn btn-success add-btn"><i class="fas fa-plus"></i> Agregar</a>
    </div>

    <!-- Agregar scripts de jQuery, DataTables y sus extensiones -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaProductos').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Reporte de Productos',
                        className: 'excel-btn', // Agrega la clase de estilo para el botón de Excel
                        text: '<i class="far fa-file-excel excel-icon"></i>' // Agrega el icono de Excel
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Reporte de Productos',
                        orientation: 'portrait', //portrait o landscape
                        pageSize: 'A4',
                        className: 'pdf-btn', // Agrega la clase de estilo para el botón de PDF
                        text: '<i class="far fa-file-pdf pdf-icon"></i>' // Agrega el icono de PDF
                    }
                ]
            });
        });
    </script>
</body>
</html>
