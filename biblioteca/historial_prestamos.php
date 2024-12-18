<?php
session_start();
require_once 'config.php';

$idprof = $_SESSION['idprof'];

// Obtener el historial de préstamos activos para el usuario logueado
$sql = "SELECT p.*, l.Titulo FROM Prestamo p
        JOIN Libro l ON p.IdEjemplar = l.IdEjemplar
        WHERE p.IdProf = :idprof AND p.FechaFin IS NULL"; 
$query = $conexion->prepare($sql);
$query->execute(['idprof' => $idprof]);

// Procesar devolución de libro
if (isset($_GET['devolver'])) {
    $idprestamo = $_GET['devolver'];
    $sql_devolver = "UPDATE Prestamo SET FechaFin = NOW() WHERE IdPrestamo = :idprestamo";
    $query_devolver = $conexion->prepare($sql_devolver);
    $query_devolver->execute(['idprestamo' => $idprestamo]);
    header("Location: historial_prestamos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Préstamos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Historial de Préstamos</h2>
        <a href="listado_profesores.php" class="btn btn-outline-primary mb-4">Volver al listado de libros</a>

        <?php if ($query->rowCount() > 0): ?>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Título del Libro</th>
                        <th>Fecha de Préstamo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prestamo = $query->fetch()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prestamo['Titulo']); ?></td>
                            <td><?php echo htmlspecialchars($prestamo['FechaInicio']); ?></td>
                            <td>
                                <!-- Botón para devolver el libro -->
                                <a href="historial_prestamos.php?devolver=<?php echo $prestamo['IdPrestamo']; ?>" 
                                   class="btn btn-warning btn-sm" onclick="return confirm('¿Seguro que deseas devolver este libro?')">Devolver Libro</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No tienes préstamos activos.</div>
        <?php endif; ?>
    </div>
</body>
</html>
