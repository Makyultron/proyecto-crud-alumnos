<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require_once 'conexion.php';

    // Recuperamos los datos de texto del formulario
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $estatus = $_POST['estatus'] ?? 0;
    
    // --- LÓGICA PARA LA CARGA DE LA IMAGEN ---
    
    $nombre_foto = ''; // Variable para guardar el nombre de la foto, la iniciamos vacía.

    // Verificamos si se subió un archivo y si no hubo errores
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        
        $directorio_subida = 'uploads/'; // La carpeta que creamos
        
        // Creamos un nombre de archivo único para evitar sobreescribir imágenes
        // Usamos el tiempo actual y el nombre original del archivo.
        $nombre_foto = time() . '_' . basename($_FILES["foto"]["name"]);
        
        $ruta_destino = $directorio_subida . $nombre_foto;

        // Movemos el archivo desde su ubicación temporal a nuestro directorio de destino
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            // El archivo se subió correctamente. El nombre ya está en $nombre_foto.
        } else {
            // Hubo un error al mover el archivo. Dejamos el nombre de la foto vacío.
            $nombre_foto = '';
            echo "Hubo un error al subir la foto.";
        }
    }

    // --- FIN DE LA LÓGICA DE IMAGEN ---

    // Preparamos la consulta SQL, ahora incluyendo la columna 'foto'
    $sql = "INSERT INTO ALUMNOS (nombre, apellido, direccion, telefono, correo, fecha_nacimiento, status, foto) 
            VALUES (:nombre, :apellido, :direccion, :telefono, :correo, :fecha_nacimiento, :status, :foto)";

    $sentencia = $pdo->prepare($sql);

    try {
        // Ejecutamos la consulta, añadiendo el nuevo parámetro :foto
        $sentencia->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':correo' => $correo,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':status' => $estatus,
            ':foto' => $nombre_foto // Aquí pasamos el nombre del archivo
        ]);

        header('Location: index.php');
        exit();

    } catch (PDOException $e) {
        echo "Error al registrar el alumno: " . $e->getMessage();
    }

} else {
    header('Location: index.php');
    exit();
}
?>