<?php

session_start();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['usuario_id'])) {
  // El usuario ha iniciado sesión, obtener el ID de usuario
  $usuario_id = $_SESSION['usuario_id'];

  // Ahora puedes utilizar el ID de usuario, por ejemplo, para consultas a la base de datos
  // ...
} else {
  // El usuario no ha iniciado sesión, redirigir a login
  header('Location:./index');
  exit;
}