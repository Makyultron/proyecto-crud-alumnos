<?php
session_start(); // Es necesario iniciar la sesión para poder destruirla.
session_unset(); // Limpia todas las variables de sesión.
session_destroy(); // Destruye la sesión actual.
header('Location: login.php'); // Redirige al usuario a la página de login.
exit();
?>