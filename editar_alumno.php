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

// Incluimos la conexión para poder usar $pdo
require_once 'conexion.php';

// Verificamos si se nos ha pasado un ID por la URL
if (!isset($_GET['id'])) {
    // Si no hay ID, redirigimos a la página principal
    header('Location: index.php');
    exit();
}

$id_alumno = $_GET['id'];

// Preparamos y ejecutamos la consulta para obtener los datos del alumno específico
$sql = "SELECT * FROM ALUMNOS WHERE id = :id";
$sentencia = $pdo->prepare($sql);
$sentencia->execute([':id' => $id_alumno]);
$alumno = $sentencia->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra ningún alumno con ese ID, redirigimos
if (!$alumno) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
</head>
<body>

    <h1>Editar Alumno</h1>
    
    <form action="actualizar_alumno.php" method="POST" enctype="multipart/form.php">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($alumno['id']); ?>">
        <input type="hidden" name="id" id="edit_id" value="<?php echo htmlspecialchars($alumno['id']); ?>">
<input type="hidden" name="foto_actual" value="<?php echo htmlspecialchars($alumno['foto']); ?>">
        <p>
            <input type="file" name="foto">
        </p>

        <p>
            <label for="nombre">Nombre:</label><br>
            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($alumno['nombre']); ?>" required>
        </p>
        <p>
            <label for="apellido">Apellido:</label><br>
            <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($alumno['apellido']); ?>" required>
        </p>
        <p>
            <label for="direccion">Dirección:</label><br>
            <input type="text" name="direccion" id="direccion" value="<?php echo htmlspecialchars($alumno['direccion']); ?>">
        </p>
        <p>
            <label for="telefono">Teléfono:</label><br>
            <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($alumno['telefono']); ?>">
        </p>
        <p>
            <label for="correo">Correo Electrónico:</label><br>
            <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($alumno['correo']); ?>" required>
        </p>
        <p>
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label><br>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?>">
        </p>
        <p>
            <label for="estatus">Estatus:</label><br>
            <select name="estatus" id="estatus">
                <option value="1" <?php if($alumno['status'] == 1) echo 'selected'; ?>>Activo</option>
                <option value="0" <?php if($alumno['status'] == 0) echo 'selected'; ?>>Inactivo</option>
            </select>
        </p>
        <p>
            <button type="submit">Actualizar Alumno</button>
        </p>
    </form>

</body>
</html>