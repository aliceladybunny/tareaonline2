<?php
session_start();
require_once 'config.php';

$msgresultado = "";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: profesores.php"); 
    exit();
}

$idprofesor = $_GET['id'];

try {
    // Obtener los datos del profesor a editar
    $sql = "SELECT * FROM Profesor WHERE IdProf = :idprofesor";
    $query = $conexion->prepare($sql);
    $query->execute(['idprofesor' => $idprofesor]);

    if ($query->rowCount() == 0) {
        header("Location: profesores.php"); 
        exit();
    }

    $profesor = $query->fetch(PDO::FETCH_ASSOC);

    // Si el formulario se ha enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $apellido1 = $_POST['apellido1'];
        $apellido2 = $_POST['apellido2'];
        $email = $_POST['email'];
        $rol = $_POST['rol'];
        $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $profesor['Password'];
        $foto = $profesor['Foto'];

        // Subir la foto si se ha proporcionado
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $foto = $_FILES['foto']['name'];
            move_uploaded_file($_FILES['foto']['tmp_name'], 'images/' . $foto);
        }

        // Actualizar los datos del profesor en la base de datos
        try {
            $sql = "UPDATE Profesor SET Nombre = :nombre, Apellido1 = :apellido1, Apellido2 = :apellido2, Email = :email, Rol = :rol, Password = :password, Foto = :foto WHERE IdProf = :idprofesor";
            $query = $conexion->prepare($sql);
            $query->execute([
                'nombre' => $nombre,
                'apellido1' => $apellido1,
                'apellido2' => $apellido2,
                'email' => $email,
                'rol' => $rol,
                'password' => $password,
                'foto' => $foto,
                'idprofesor' => $idprofesor
            ]);

            $msgresultado = '<div class="alert alert-success">Profesor actualizado correctamente.</div>';
        } catch (PDOException $ex) {
            $msgresultado = '<div class="alert alert-danger">Hubo un error al actualizar al profesor: ' . $ex->getMessage() . '</div>';
        }
    }
} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger">Hubo un error al obtener los datos del profesor: ' . $ex->getMessage() . '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Profesor</title>
    <link rel="stylesheet" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Editar Profesor</h2>
        <a href="profesores.php" class="btn btn-primary mb-4">Volver al Listado de Profesores</a>

        <?php echo $msgresultado; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($profesor['Nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido1">Primer Apellido</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1" value="<?php echo htmlspecialchars($profesor['Apellido1']); ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido2">Segundo Apellido</label>
                <input type="text" class="form-control" id="apellido2" name="apellido2" value="<?php echo htmlspecialchars($profesor['Apellido2']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($profesor['Email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña (dejar en blanco para no cambiarla)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="foto">Foto (opcional)</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
                <?php if ($profesor['Foto']): ?>
                    <img src="images/<?php echo htmlspecialchars($profesor['Foto']); ?>" width="100" class="mt-2" alt="Foto Actual">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select class="form-control" id="rol" name="rol">
                    <option value="0" <?php echo $profesor['Rol'] == 0 ? 'selected' : ''; ?>>Profesor</option>
                    <option value="1" <?php echo $profesor['Rol'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Actualizar Profesor</button>
        </form>
    </div>
</body>
</html>
