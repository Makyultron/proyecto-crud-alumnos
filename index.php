<?php
// session_start() debe ser la primera línea para poder usar $_SESSION.
session_start();

// Verificamos si la variable de sesión 'usuario_id' NO está definida.
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'conexion.php';

$sql = "SELECT * FROM ALUMNOS";
$sentencia = $pdo->prepare($sql);
$sentencia->execute();
$alumnos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" />
</head>
<body>

<div class="container mt-4">
    <h1>Lista de Alumnos</h1>
    <p>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>! <a href="logout.php">Cerrar Sesión</a></p>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tablaAlumnos">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($alumnos) > 0):
                    foreach ($alumnos as $alumno): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alumno['id']); ?></td>
                            <td>
                                <?php if (!empty($alumno['foto'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($alumno['foto']); ?>" alt="Foto de Perfil" width="50">
                                <?php else: ?>
                                    Sin foto
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['correo']); ?></td>
                            <td><?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?></td>
                            <td>
                                <a href="editar_alumno.php?id=<?php echo $alumno['id']; ?>" class="btn btn-sm btn-warning btn-editar">Editar</a>
                                <a href="eliminar_alumno.php?id=<?php echo $alumno['id']; ?>" class="btn btn-sm btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="7">No hay alumnos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <hr>
    
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAnadirAlumno">
      Añadir Nuevo Alumno
    </button>
</div><div class="modal fade" id="modalAnadirAlumno" tabindex="-1" aria-labelledby="modalAnadirAlumnoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalAnadirAlumnoLabel">Añadir Nuevo Alumno</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="crear_alumno.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="mb-3"><label for="nombre" class="form-label">Nombre:</label><input type="text" class="form-control" name="nombre" required></div>
            <div class="mb-3"><label for="apellido" class="form-label">Apellido:</label><input type="text" class="form-control" name="apellido" required></div>
            <div class="mb-3"><label for="direccion" class="form-label">Dirección:</label><input type="text" class="form-control" name="direccion"></div>
            <div class="mb-3"><label for="telefono" class="form-label">Teléfono:</label><input type="text" class="form-control" name="telefono"></div>
            <div class="mb-3"><label for="correo" class="form-label">Correo Electrónico:</label><input type="email" class="form-control" name="correo" required></div>
            <div class="mb-3"><label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label><input type="date" class="form-control" name="fecha_nacimiento"></div>
            <div class="mb-3"><label for="foto" class="form-label">Foto:</label><input type="file" class="form-control" name="foto"></div>
            <div class="mb-3"><label for="estatus" class="form-label">Estatus:</label><select name="estatus" class="form-select"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar Alumno</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditarAlumno" tabindex="-1" aria-labelledby="modalEditarAlumnoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalEditarAlumnoLabel">Editar Alumno</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actualizar_alumno.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="foto_actual" id="edit_foto_actual">
            <div class="mb-3"><label for="edit_nombre" class="form-label">Nombre:</label><input type="text" class="form-control" name="nombre" id="edit_nombre" required></div>
            <div class="mb-3"><label for="edit_apellido" class="form-label">Apellido:</label><input type="text" class="form-control" name="apellido" id="edit_apellido" required></div>
            <div class="mb-3"><label for="edit_direccion" class="form-label">Dirección:</label><input type="text" class="form-control" name="direccion" id="edit_direccion"></div>
            <div class="mb-3"><label for="edit_telefono" class="form-label">Teléfono:</label><input type="text" class="form-control" name="telefono" id="edit_telefono"></div>
            <div class="mb-3"><label for="edit_correo" class="form-label">Correo:</label><input type="email" class="form-control" name="correo" id="edit_correo" required></div>
            <div class="mb-3"><label for="edit_fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label><input type="date" class="form-control" name="fecha_nacimiento" id="edit_fecha_nacimiento"></div>
            <div class="mb-3"><label for="edit_foto" class="form-label">Foto (dejar en blanco para no cambiar):</label><input type="file" class="form-control" name="foto" id="edit_foto"></div>
            <div class="mb-3"><label for="edit_estatus" class="form-label">Estatus:</label><select name="estatus" id="edit_estatus" class="form-select"><option value="1">Activo</option><option value="0">Inactivo</option></select></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaAlumnos').DataTable();

        // CÓDIGO JAVASCRIPT/JQUERY PARA MANEJAR EL MODAL DE EDICIÓN
        $('#tablaAlumnos').on('click', '.btn-editar', function(event) {
            event.preventDefault();
            
            var url = $(this).attr('href');
            var id = url.split('id=')[1];

            $.ajax({
                url: 'obtener_alumno.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(alumno) {
                    if (!alumno.error) {
                        // Rellenamos el formulario del modal de edición
                        $('#edit_id').val(alumno.id);
                        $('#edit_foto_actual').val(alumno.foto); // Guardamos el nombre de la foto actual
                        $('#edit_nombre').val(alumno.nombre);
                        $('#edit_apellido').val(alumno.apellido);
                        $('#edit_direccion').val(alumno.direccion);
                        $('#edit_telefono').val(alumno.telefono);
                        $('#edit_correo').val(alumno.correo);
                        $('#edit_fecha_nacimiento').val(alumno.fecha_nacimiento);
                        $('#edit_estatus').val(alumno.status);

                        // Mostramos el modal de edición
                        var editModal = new bootstrap.Modal(document.getElementById('modalEditarAlumno'));
                        editModal.show();
                    } else {
                        alert(alumno.error);
                    }
                },
                error: function() {
                    alert('Error al obtener los datos del alumno.');
                }
            });
        });
    });
</script>

</body>
</html>