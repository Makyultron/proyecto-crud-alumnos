<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require_once 'conexion.php';

    // Recuperamos los datos de texto y el ID
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $estatus = $_POST['estatus'] ?? 0;
    
    // Recuperamos el nombre de la foto actual desde el campo oculto
    $nombre_foto = $_POST['foto_actual'] ?? '';

    if (!$id) {
        echo "Error: ID de alumno no proporcionado.";
        exit();
    }

    // --- LÓGICA PARA ACTUALIZAR LA IMAGEN ---
    // Verificamos si se subió un archivo nuevo
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        
        $directorio_subida = 'uploads/';
        // Creamos un nombre de archivo único
        $nombre_foto_nueva = time() . '_' . basename($_FILES["foto"]["name"]);
        $ruta_destino = $directorio_subida . $nombre_foto_nueva;

        // Si se mueve el nuevo archivo correctamente, actualizamos el nombre de la foto
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            // (Opcional) Borrar la foto antigua si existía para no acumular basura
            if (!empty($nombre_foto)) {
                unlink($directorio_subida . $nombre_foto);
            }
            $nombre_foto = $nombre_foto_nueva; // Usaremos el nombre de la nueva foto
        }
    }
    // Si no se subió un archivo nuevo, la variable $nombre_foto conservará el valor antiguo.

    // Preparamos la consulta SQL UPDATE
    $sql = "UPDATE ALUMNOS SET 
                nombre = :nombre, 
                apellido = :apellido, 
                direccion = :direccion, 
                telefono = :telefono, 
                correo = :correo, 
                fecha_nacimiento = :fecha_nacimiento, 
                status = :status,
                foto = :foto 
            WHERE id = :id";

    $sentencia = $pdo->prepare($sql);

    try {
        $sentencia->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':correo' => $correo,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':status' => $estatus,
            ':foto' => $nombre_foto,
            ':id' => $id
        ]);

        header('Location: index.php');
        exit();

    } catch (PDOException $e) {
        echo "Error al actualizar el alumno: " . $e->getMessage();
    }

} else {
    header('Location: index.php');
    exit();
}
?>