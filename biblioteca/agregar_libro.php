<?php
session_start();
require_once 'config.php';

$msgresultado = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $fechaPublicacion = $_POST['fecha_publicacion'];
    $editorial = $_POST['editorial'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $portada = $_FILES['portada']['name'];

    if ($portada) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["portada"]["name"]);
        move_uploaded_file($_FILES["portada"]["tmp_name"], $target_file);
    }

    try {
        $sql = "
        INSERT INTO Libro (ISBN, Titulo, FechaPublicacion, Editorial, Descripcion, Precio, Portada)
        VALUES (:isbn, :titulo, :fechaPublicacion, :editorial, :descripcion, :precio, :portada)
        ";

        $query = $conexion->prepare($sql);
        $query->execute([
            'isbn' => $isbn,
            'titulo' => $titulo,
            'fechaPublicacion' => $fechaPublicacion,
            'editorial' => $editorial,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'portada' => $portada
        ]);

        $msgresultado = '<div class="alert alert-success">¡El libro se ha agregado correctamente!</div>';
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">Error al agregar el libro: ' . $ex->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Libro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">
                <img src="images/libro.png" width="40" class="mr-2">Agregar Libro
            </h2>
            <a href="listado_admin.php" class="btn btn-outline-primary">Volver al Listado</a>
        </div>

        <?php echo $msgresultado; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_publicacion">Fecha de Publicación</label>
                        <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" required>
                    </div>
                    <div class="form-group">
                        <label for="editorial">Editorial</label>
                        <input type="text" class="form-control" id="editorial" name="editorial" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio (€)</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="form-group">
                        <label for="portada">Portada</label>
                        <input type="file" class="form-control" id="portada" name="portada" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success">Agregar Libro</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
