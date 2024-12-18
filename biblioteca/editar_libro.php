<?php
session_start();
require_once 'config.php';

$msgresultado = "";

if (isset($_GET['id'])) {
    $idEjemplar = $_GET['id'];

    // Consulta para obtener los datos del libro
    try {
        $sql = "SELECT * FROM Libro WHERE IdEjemplar = :idEjemplar";
        $query = $conexion->prepare($sql);
        $query->execute(['idEjemplar' => $idEjemplar]);

        // Si no se encuentra el libro, redirigimos o mostramos un error
        if ($query->rowCount() == 0) {
            $msgresultado = '<div class="alert alert-danger">El libro no existe.</div>';
        } else {
            $libro = $query->fetch();
        }
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">Error al recuperar los datos del libro.</div>';
    }
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $fechaPublicacion = $_POST['fechaPublicacion'];
    $editorial = $_POST['editorial'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $portada = $_POST['portada'];

    // Actualizamos los datos en la base de datos
    try {
        $sql = "UPDATE Libro SET ISBN = :isbn, Titulo = :titulo, FechaPublicacion = :fechaPublicacion, 
                Editorial = :editorial, Descripcion = :descripcion, Precio = :precio, Portada = :portada
                WHERE IdEjemplar = :idEjemplar";
        $query = $conexion->prepare($sql);
        $query->execute([
            'isbn' => $isbn,
            'titulo' => $titulo,
            'fechaPublicacion' => $fechaPublicacion,
            'editorial' => $editorial,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'portada' => $portada,
            'idEjemplar' => $idEjemplar
        ]);

        $msgresultado = '<div class="alert alert-success">El libro se ha actualizado correctamente.</div>';
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">Error al actualizar el libro.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <link rel="stylesheet" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Editar Libro</h2>

        <?php echo $msgresultado; ?>

        <!-- Formulario de Edición -->
        <?php if (isset($libro)): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo htmlspecialchars($libro['ISBN']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo htmlspecialchars($libro['Titulo']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="fechaPublicacion">Fecha de Publicación</label>
                    <input type="date" name="fechaPublicacion" id="fechaPublicacion" class="form-control" value="<?php echo htmlspecialchars($libro['FechaPublicacion']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="editorial">Editorial</label>
                    <input type="text" name="editorial" id="editorial" class="form-control" value="<?php echo htmlspecialchars($libro['Editorial']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($libro['Descripcion']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" name="precio" id="precio" class="form-control" value="<?php echo htmlspecialchars($libro['Precio']); ?>" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="portada">Portada (nombre de archivo)</label>
                    <input type="text" name="portada" id="portada" class="form-control" value="<?php echo htmlspecialchars($libro['Portada']); ?>">
                </div>

                <button type="submit" class="btn btn-success">Actualizar Libro</button>
                <a href="listado_admin.php" class="btn btn-secondary">Volver al Listado</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
