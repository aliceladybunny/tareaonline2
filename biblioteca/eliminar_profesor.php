<?php
session_start();
require_once 'config.php';

$msgresultado = "";

if (isset($_GET['id'])) {
    $idProfesor = $_GET['id'];

    // Eliminar el profesor de la base de datos
    try {
        $sql = "DELETE FROM Profesor WHERE IdProf = :idProfesor";
        $query = $conexion->prepare($sql);
        $query->execute(['idProfesor' => $idProfesor]);

        if ($query->rowCount() > 0) {
            $msgresultado = '<div class="alert alert-success">Profesor eliminado correctamente.</div>';
        } else {
            $msgresultado = '<div class="alert alert-warning">El profesor no se pudo eliminar (puede que ya no exista).</div>';
        }
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">Error al eliminar el profesor.</div>';
    }
} else {
    $msgresultado = '<div class="alert alert-danger">No se ha proporcionado un ID de profesor para eliminar.</div>';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Profesor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Eliminar Profesor</h2>

        <?php echo $msgresultado; ?>

        <a href="profesores.php" class="btn btn-secondary">Volver al Listado</a>
    </div>
</body>
</html>
