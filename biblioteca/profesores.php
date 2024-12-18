<?php
session_start();
require_once 'config.php';

$msgresultado = "";

try {
    $sql = "SELECT * FROM Profesor";
    $query = $conexion->prepare($sql);
    $query->execute();

    if ($query) {
        $msgresultado = '<div class="alert alert-success">' . 
                        "La consulta de profesores se realizó correctamente!! :)" . 
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
    <title>Listado de Profesores</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Botones de acciones -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Listado de Profesores</h2>
            <a href="cerrar_sesion.php" class="btn btn-danger">Cerrar sesión</a>
        </div>

        <!-- Mensajes de resultado -->
        <?php echo $msgresultado; ?>

        <!-- Tabla de Profesores -->
        <div class="card shadow-sm">
            <div class="card-body">
                <a href="agregar_profesor.php" class="btn btn-success mb-3">Añadir Profesor</a>
                <?php if ($query->rowCount() > 0): ?>
                    <table class="table table-striped table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($profesor = $query->fetch()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($profesor['IdProf']); ?></td>
                                    <td><?php echo htmlspecialchars($profesor['Nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($profesor['Email']); ?></td>
                                    <td>
                                        <!-- Botón para editar -->
                                        <a href="editar_profesor.php?id=<?php echo $profesor['IdProf']; ?>" class="btn btn-warning">Editar</a>

                                        <!-- Botón para eliminar -->
                                        <a href="eliminar_profesor.php?id=<?php echo $profesor['IdProf']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este profesor?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        No hay profesores registrados en la base de datos.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
