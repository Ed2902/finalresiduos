<?php

// Iniciar la sesión
session_start();

// Verificar si el usuario no ha iniciado sesión
if (!isset($_SESSION["usuario"])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: ../login/login.html");
    exit(); // Asegurarse de que el script no siga ejecutándose después de la redirección
}
?>
