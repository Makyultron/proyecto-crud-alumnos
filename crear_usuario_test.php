<?php
require_once 'conexion.php';

// Define los datos de tu primer usuario administrador
$usuario = 'admin';
$password_plano = '12345'; // Una contraseña simple para recordar

// ¡La magia de la seguridad! Hasheamos la contraseña.
$password_hasheado = password_hash($password_plano, PASSWORD_DEFAULT);

// Preparamos la consulta para insertar el nuevo usuario
$sql = "INSERT INTO usuarios (usuario, password) VALUES (:usuario, :password)";
$sentencia = $pdo->prepare($sql);

try {
    $sentencia->execute([
        ':usuario' => $usuario,
        ':password' => $password_hasheado
    ]);
    echo "<h1>¡Usuario 'admin' creado exitosamente!</h1>";
    echo "<p>Ya puedes borrar este archivo (crear_usuario_test.php).</p>";

} catch (PDOException $e) {
    echo "Error al crear el usuario: " . $e->getMessage();
}
?>