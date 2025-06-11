<?php
// Incluimos la conexión para poder usar $pdo
require_once 'conexion.php';

// Verificamos que se nos ha pasado un ID por la URL.
if (!isset($_GET['id'])) {
    // Si no hay ID, devolvemos un error en formato JSON.
    echo json_encode(['error' => 'No se proporcionó un ID']);
    exit();
}

$id_alumno = $_GET['id'];

// Preparamos y ejecutamos la consulta para obtener los datos del alumno específico
$sql = "SELECT * FROM ALUMNOS WHERE id = :id";
$sentencia = $pdo->prepare($sql);
$sentencia->execute([':id' => $id_alumno]);
$alumno = $sentencia->fetch(PDO::FETCH_ASSOC);

// Le decimos al navegador que la respuesta que enviaremos es de tipo JSON.
header('Content-Type: application/json');

// Si se encontró un alumno, lo devolvemos en formato JSON.
if ($alumno) {
    echo json_encode($alumno);
} else {
    // Si no, devolvemos un error.
    echo json_encode(['error' => 'Alumno no encontrado']);
}
?>