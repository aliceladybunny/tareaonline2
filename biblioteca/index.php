<?php
session_start();
require_once 'config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM profesor WHERE Email = :email LIMIT 1";
        $query = $conexion->prepare($sql);
        $query->execute(['email' => $email]);

        if ($query->rowCount() > 0) {
            $usuario = $query->fetch();

            // Verificar la contraseña
            if (password_verify($password, $usuario['Password'])) {
                $_SESSION['usuario'] = $usuario['Email'];
                $_SESSION['idprof'] = $usuario['IdProf'];
                $_SESSION['rol'] = $usuario['Rol']; 
                session_regenerate_id();

                // Redirigir según el rol
                if ($_SESSION['rol'] == 1) {
                    header("Location: listado_admin.php");
                } else {
                    header("Location: listado_profesores.php");
                }
                exit();
            } else {
                echo '<div class="alert alert-danger text-center mt-3">Contraseña incorrecta.</div>';
            }
        } else {
            echo '<div class="alert alert-danger text-center mt-3">Correo no encontrado.</div>';
        }
    } catch (PDOException $ex) {
        echo '<div class="alert alert-danger text-center mt-3">Error al iniciar sesión: ' . $ex->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca IES SAN SEBASTIAN - Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Biblioteca IES SAN SEBASTIAN</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-3">Iniciar sesión</h2>

                        <!-- Formulario de inicio de sesión -->
                        <form method="post" action="index.php">
                            <input type="hidden" name="session_id" value="<?php echo session_id(); ?>">

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Iniciar sesión</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="adduser.php" class="btn btn-link p-0">¿No tienes cuenta? Regístrate aquí</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
