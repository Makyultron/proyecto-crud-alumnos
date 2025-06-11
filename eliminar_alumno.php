<?php
// session_start() debe ser la primera línea para poder usar $_SESSION.
session_start();

// Verificamos si la variable de sesión 'usuario_id' NO está definida.
// Si no existe, es porque el usuario no ha iniciado sesión.
if (!isset($_SESSION['usuario_id'])) {
    // Si no ha iniciado sesión, lo redirigimos a la página de login.
    header('Location: login.php');
    exit(); // Detenemos la ejecución del resto de la página.
}

// Verificamos que se nos ha pasado un ID por la URL.
if (isset($_GET['id'])) {

    require_once 'conexion.php';

    $id_alumno = $_GET['id'];

    // Preparamos la consulta SQL para eliminar el registro con el ID correspondiente.
    $sql = "DELETE FROM ALUMNOS WHERE id = :id";
    $sentencia = $pdo->prepare($sql);

    try {
        // Ejecutamos la consulta, pasando el ID como parámetro.
        $sentencia->execute([':id' => $id_alumno]);

        // Redirigimos de vuelta a la página principal.
        header('Location: index.php');
        exit();

    } catch (PDOException $e) {
        echo "Error al eliminar el alumno: " . $e->getMessage();
    }

} else {
    // Si no se pasó un ID, simplemente redirigimos a la página principal.
    echo "Error: No se proporcionó un ID de alumno.";
    // Opcionalmente, redirigir: header('Location: index.php');
}
?>