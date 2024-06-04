<?php
 require_once('../conexion.php');

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Recuperar datos del formulario
$nit = $_POST['id_cliente'];
$nombre = $_POST['nombre'];
$representante_legal = $_POST['representantelegal'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$fecha_registro = $_POST['fecha'];

// Crear una carpeta para el cliente
$targetDirectory = "./guardar/";
$clientFolder = $targetDirectory . $nombre . "/";
if (!file_exists($clientFolder)) {
    mkdir($clientFolder, 0777, true); // Se crea la carpeta si no existe
}

// Rutas de los archivos
$camara_comercio = $clientFolder . $_FILES['camara']['name'];
$rut = $clientFolder . $_FILES['rut']['name'];
$cc_representante = $clientFolder . $_FILES['cc']['name'];
$certificacion_comercial = $clientFolder . $_FILES['comercial']['name'];
$certificacion_bancaria = $clientFolder . $_FILES['bancaria']['name'];
$circular_170 = $clientFolder . $_FILES['circular']['name'];
$acuerdos_seguridad = $clientFolder . $_FILES['seguridad']['name'];
$estados_financieros = $clientFolder . $_FILES['financieros']['name'];
$autorizacion_tratamiento_datos = $clientFolder . $_FILES['autorizacion']['name'];
$visita = $clientFolder . $_FILES['visita']['name'];
$antecedentes_judiciales = $clientFolder . $_FILES['antecedentes']['name'];

// Mover archivos subidos a la carpeta del cliente
move_uploaded_file($_FILES['camara']['tmp_name'], $camara_comercio);
move_uploaded_file($_FILES['rut']['tmp_name'], $rut);
move_uploaded_file($_FILES['cc']['tmp_name'], $cc_representante);
move_uploaded_file($_FILES['comercial']['tmp_name'], $certificacion_comercial);
move_uploaded_file($_FILES['bancaria']['tmp_name'], $certificacion_bancaria);
move_uploaded_file($_FILES['circular']['tmp_name'], $circular_170);
move_uploaded_file($_FILES['seguridad']['tmp_name'], $acuerdos_seguridad);
move_uploaded_file($_FILES['financieros']['tmp_name'], $estados_financieros);
move_uploaded_file($_FILES['autorizacion']['tmp_name'], $autorizacion_tratamiento_datos);
move_uploaded_file($_FILES['visita']['tmp_name'], $visita);
move_uploaded_file($_FILES['antecedentes']['tmp_name'], $antecedentes_judiciales);

// Insertar datos en la base de datos
$sql = "INSERT INTO cliente (id_cliente, nombre, representantelegal,correo, telefono, direccion, fecha_registro, camara_comercio, rut, cc_representante, certificacion_comercial, certificacion_bancaria, circular_170, acuerdos_seguridad, estados_financieros, autorizacion_tratamiento_datos, visita, antecedentes_judiciales) VALUES ('$nit', '$nombre', '$representante_legal', '$correo', '$telefono', '$direccion', '$fecha_registro', '$camara_comercio', '$rut', '$cc_representante', '$certificacion_comercial', '$certificacion_bancaria', '$circular_170', '$acuerdos_seguridad', '$estados_financieros', '$autorizacion_tratamiento_datos', '$visita', '$antecedentes_judiciales')";

if ($conn->query($sql) === TRUE) {
    // Redirigir a otra página después de insertar los datos
    header('Location: ./tabla.php');
    exit(); // Asegurar que el script termina después de la redirección
} else {
    echo "Error al insertar registro: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
