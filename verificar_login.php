<?php
// session_start() es la primera línea que debe haber para poder usar sesiones.
session_start();

// Verificamos que el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'conexion.php';

    $usuario_form = $_POST['usuario'];
    $password_form = $_POST['password'];

    // Buscamos en la base de datos un usuario que coincida
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $sentencia = $pdo->prepare($sql);
    $sentencia->execute([':usuario' => $usuario_form]);
    $usuario_db = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Verificamos si se encontró un usuario Y si la contraseña coincide
    // password_verify() compara la contraseña del formulario con el hash de la BD.
    if ($usuario_db && password_verify($password_form, $usuario_db['password'])) {

        // ¡Login correcto! Guardamos datos en la sesión.
        $_SESSION['usuario_id'] = $usuario_db['id'];
        $_SESSION['usuario_nombre'] = $usuario_db['usuario'];

        // Redirigimos al usuario a la página principal del CRUD
        header('Location: index.php');
        exit();

    } else {
        // Si no coincide, lo regresamos al login con un mensaje de error.
        header('Location: login.php?error=1');
        exit();
    }
} else {
    // Si no se envió por POST, redirigimos a la página de login.
    header('Location: login.php');
    exit();
}
?>