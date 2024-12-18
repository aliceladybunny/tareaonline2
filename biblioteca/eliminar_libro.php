<?php
session_start();
require_once 'config.php';

$msgresultado = "";

if (isset($_GET['id'])) {
    $idEjemplar = $_GET['id'];

    // Eliminar el libro de la base de datos
    try {
        $sql = "DELETE FROM Libro WHERE IdEjemplar = :idEjemplar";
        $query = $conexion->prepare($sql);
        $query->execute(['idEjemplar' => $idEjemplar]);

        // Verificar si se eliminó correctamente
        if ($query->rowCount() > 0) {
            $msgresultado = '<div class="alert alert-success">El libro ha sido eliminado correctamente.</div>';
        } else {
            $msgresultado = '<div class="alert alert-warning">No se pudo eliminar el libro. Puede que ya no exista.</div>';
        }
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">Error al eliminar el libro.</div>';
    }
} else {
    $msgresultado = '<div class="alert alert-danger">No se ha proporcionado un ID de libro para eliminar.</div>';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Libro</title>
    <link rel="stylesheet" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Eliminar Libro</h2>

        <?php echo $msgresultado; ?>

        <a href="listado_admin.php" class="btn btn-secondary">Volver al Listado</a>
    </div>
</body>
</html>
