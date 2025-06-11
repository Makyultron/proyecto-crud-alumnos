<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRUD de Alumnos</title>
    </head>
<body>

    <h1>Iniciar Sesión</h1>

    <?php
    // Si hay un mensaje de error en la URL (lo usaremos más adelante), lo mostramos aquí.
    if (isset($_GET['error'])) {
        echo '<p style="color:red;">Usuario o contraseña incorrectos.</p>';
    }
    ?>

    <form action="verificar_login.php" method="POST">
        <p>
            <label for="usuario">Usuario:</label><br>
            <input type="text" name="usuario" id="usuario" required>
        </p>
        <p>
            <label for="password">Contraseña:</label><br>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <button type="submit">Entrar</button>
        </p>
    </form>

</body>
</html>