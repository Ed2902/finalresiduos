<?php

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (aquí debes agregar tus propias credenciales)
    $servername = "localhost";
    $username = "fastways_Programador";
    $password = "Jsqpmlqpors2902";
    $dbname = "fastways_appfastway";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Consulta SQL para verificar el usuario y la contraseña
    $sql = "SELECT * FROM usuario WHERE nombre = '$usuario' AND contraseña = '$contrasena'";
    $result = $conn->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Inicio de sesión exitoso
        session_start();
        $_SESSION["usuario"] = $usuario; // Guardar el nombre de usuario en la sesión
        header("Location: ../home/home.php"); // Redirigir al usuario a la página de inicio
        exit();
    } else {
        // Usuario o contraseña incorrectos
        echo "Usuario o contraseña incorrectos";
    }

    // Cerrar conexión
    $conn->close();
}
?>
