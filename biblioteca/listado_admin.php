<?php
session_start();
require_once 'config.php';

$msgresultado = "";

try {
    // Consulta para obtener todos los libros
    $sql = "
    SELECT IdEjemplar, ISBN, Titulo, FechaPublicacion, Editorial, Descripcion, Precio, Portada
    FROM Libro
    ";
    $query = $conexion->prepare($sql);
    $query->execute();

    // Supervisamos si la consulta se realizó correctamente
    if ($query) {
        $msgresultado = '<div class="alert alert-success">' . 
                        "La consulta de libros se realizó correctamente!! :)" . 
                        '</div>';
    }
} catch (PDOException $ex) {
    $msgresultado = '<div class="alert alert-danger">' . 
                    "La consulta no pudo realizarse correctamente!! :(" . '</div>';
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Libros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Botón de Inicio -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">
                <img src="images/libro.png" width="40" class="mr-2">Listado de Libros
            </h2>
            <!-- Botones Cerrar sesión y Listado Profesores -->
            <div>
                <a href="profesores.php" class="btn btn-info mr-2">Listado Profesores</a>
                <a href="cerrar_sesion.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>

        <!-- Mensajes de resultado -->
        <?php echo $msgresultado; ?>

        <!-- Tabla de Libros -->
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if ($query->rowCount() > 0): ?>
                    <div class="mb-4">
                        <a href="agregar_libro.php" class="btn btn-success">Agregar Libro</a>
                    </div>
                    <table class="table table-striped table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Fecha de Publicación</th>
                                <th>Editorial</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Portada</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($fila = $query->fetch()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fila['ISBN']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['Titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['FechaPublicacion']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['Editorial']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['Descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['Precio']); ?> €</td>
                                    <td>
                                        <?php if ($fila['Portada']): ?>
                                            <img src="images/<?php echo htmlspecialchars($fila['Portada']); ?>" width="40" class="rounded">
                                        <?php else: ?>
                                            ----
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="editar_libro.php?id=<?php echo $fila['IdEjemplar']; ?>" class="btn btn-warning">Editar</a>
                                        <a href="eliminar_libro.php?id=<?php echo $fila['IdEjemplar']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este libro?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        No hay libros registrados en la base de datos.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
