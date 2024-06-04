<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cliente</title>
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
        table#tablaClientes {
            width: 100% !important; /* Asegura que la tabla ocupe todo el ancho disponible */
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="width: 90%;">
        <!-- Botón de Casa -->
        <a href="../index.html" style="text-decoration: none;">
            <button type="button" class="btn btn-light mr-2" style="border-radius: 50%;">
                <i class="fas fa-home" style="font-size: 20px; color:#fe5000;"></i>
            </button>
        </a>
        <h1 class="mt-5 mb-3">Clientes Fast Way</h1>
        <form method="GET" class="mb-4">
            <div class="form-group">
                <label for="nombre">Nombre del Cliente:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        <div class="table-responsive">
            <table id="tablaClientes" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nombre del Cliente</th>
                        <th>Representante Legal</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Fecha de Registro</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Documentos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "inventariofast";

                    // Crear conexión
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Verificar la conexión
                    if ($conn->connect_error) {
                        die("La conexión falló: " . $conn->connect_error);
                    }

                    // Consultar la base de datos
                    $sql = "SELECT id_cliente, nombre, representantelegal, telefono, direccion, fecha_registro FROM cliente";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row["nombre"]."</td>";
                            echo "<td>".$row["representantelegal"]."</td>";
                            echo "<td>".$row["telefono"]."</td>";
                            echo "<td>".$row["direccion"]."</td>";
                            echo "<td>".$row["fecha_registro"]."</td>";

                            // Calcular fecha de vencimiento
                            $fechaRegistro = new DateTime($row["fecha_registro"]);
                            $fechaVencimiento = $fechaRegistro->modify('+1 year')->format('Y-m-d');
                            echo "<td>".$fechaVencimiento."</td>";

                            // Mostrar enlace a la carpeta del cliente
                            $clientFolder = "../guardar/" . $row["nombre"] . "/";
                            echo "<td><a href='$clientFolder' target='_blank'><i class='fas fa-folder'></i> Carpeta del Cliente</a></td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>0 resultados</td></tr>";
                    }

                    // Cerrar conexión
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Botón de Agregar -->
        <button type="button" class="btn btn-success mt-3"><i class="fas fa-plus"></i> Agregar</button>
    </div>

    <!-- Agregar scripts de DataTables y Bootstrap al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaClientes').DataTable();
        });
    </script>
</body>
</html>
