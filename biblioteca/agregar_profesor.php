<?php
session_start();
require_once 'config.php';

$msgresultado = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $foto = '';

    // Subir la foto si se ha proporcionado
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], 'images/' . $foto); 
    }

    // Insertar el nuevo profesor en la base de datos
    try {
        $sql = "INSERT INTO Profesor (Nombre, Apellido1, Apellido2, Email, Rol, Password, Foto)
                VALUES (:nombre, :apellido1, :apellido2, :email, :rol, :password, :foto)";
        $query = $conexion->prepare($sql);
        $query->execute([
            'nombre' => $nombre,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2,
            'email' => $email,
            'rol' => $rol,
            'password' => $password,
            'foto' => $foto
        ]);

        $msgresultado = '<div class="alert alert-success">Profesor agregado correctamente.</div>';
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">Hubo un error al agregar al profesor: ' . $ex->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Profesor</title>
    <link rel="stylesheet" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Añadir Profesor</h2>
        <a href="profesores.php" class="btn btn-primary mb-4">Volver al Listado de Profesores</a>

        <?php echo $msgresultado; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido1">Primer Apellido</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1" required>
            </div>
            <div class="form-group">
                <label for="apellido2">Segundo Apellido</label>
                <input type="text" class="form-control" id="apellido2" name="apellido2" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto (opcional)</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select class="form-control" id="rol" name="rol">
                    <option value="0">Profesor</option>
                    <option value="1">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Añadir Profesor</button>
        </form>
    </div>
</body>
</html>
