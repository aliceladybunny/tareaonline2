<?php
session_start();

require_once 'config.php';

$titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';

$msgresultado = "";

$idprof = $_SESSION['idprof'];

try {
    // Consulta para obtener todos los libros junto con su estado de disponibilidad
    $sql = "
    SELECT libro.IdEjemplar, libro.ISBN, libro.Titulo, libro.FechaPublicacion, 
           libro.Editorial, libro.Precio, libro.Portada,
           IF(
               EXISTS (
                   SELECT 1 
                   FROM prestamo 
                   WHERE prestamo.IdEjemplar = libro.IdEjemplar AND prestamo.FechaFin IS NULL
               ), 
               'No Disponible', 
               'Disponible'
           ) AS Estado
        FROM libro
    WHERE 1";

    if ($titulo) {
        $sql .= " AND libro.Titulo LIKE :titulo";
    }

    $sql .= " ORDER BY libro.Titulo";

    $query = $conexion->prepare($sql);

    if ($titulo) {
        $query->bindValue(':titulo', '%' . $titulo . '%');
    }

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
    <title>Listado de Libros</title>
    <link rel="stylesheet" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" 
          crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Botón de Inicio -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">
                <img src="images/libro.png" width="40" class="mr-2">Listado de Libros
            </h2>
            <a href="cerrar_sesion.php" class="btn btn-danger">Cerrar sesión</a>
        </div>

        <!-- Mensajes de resultado -->
        <?php echo $msgresultado; ?>

        <!-- Tabla de Libros -->
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if ($query->rowCount() > 0): ?>
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="titulo" class="form-control" placeholder="Buscar por título" value="<?php echo htmlspecialchars($titulo); ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <button type="submit" class="btn btn-primary w-100">Buscar</button>
                            </div>
                        </div>
                    </form>
                    <div class="mb-4">
                        <a href="historial_reservas.php" class="btn btn-info">Historial de Reservas</a>
                        <a href="historial_prestamos.php" class="btn btn-info">Historial de Préstamos</a>
                    </div>
                    <table class="table table-striped table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Fecha de Publicación</th>
                                <th>Editorial</th>
                                <th>Precio</th>
                                <th>Portada</th>
                                <th>Estado</th>
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
                                    <td><?php echo htmlspecialchars($fila['Precio']); ?> €</td>
                                    <td>
                                        <?php if ($fila['Portada']): ?>
                                            <img src="images/<?php echo htmlspecialchars($fila['Portada']); ?>" width="40" class="rounded">
                                        <?php else: ?>
                                            ----
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($fila['Estado'] == 'Disponible') {
                                            echo '<span class="text-success">Disponible</span>';
                                        } else {
                                            echo '<span class="text-danger">En Préstamo</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($fila['Estado'] == 'Disponible'): ?>
                                            <a href="prestamo.php?id=<?php echo $fila['IdEjemplar']; ?>" class="btn btn-primary">Pedir Préstamo</a>
                                        <?php else: ?>
                                            <a href="reserva.php?id=<?php echo $fila['IdEjemplar']; ?>&idprof=<?php echo $_SESSION['idprof']; ?>" class="btn btn-warning">Ponerse en Cola</a>
                                        <?php endif; ?>
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
