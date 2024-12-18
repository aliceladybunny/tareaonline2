<?php
session_start();
require_once 'config.php';

$idprof = $_SESSION['idprof'];
$sql = "SELECT r.*, l.Titulo FROM Reserva r
        JOIN Libro l ON r.IdEjemplar = l.IdEjemplar
        WHERE r.IdProf = :idprof";
$query = $conexion->prepare($sql);
$query->execute(['idprof' => $idprof]);

// Procesar cancelación de reserva
if (isset($_GET['cancelar'])) {
    $idreserva = $_GET['cancelar'];
    $sql_cancelar = "DELETE FROM Reserva WHERE IdReserva = :idreserva AND IdProf = :idprof";
    $query_cancelar = $conexion->prepare($sql_cancelar);
    $query_cancelar->execute(['idreserva' => $idreserva, 'idprof' => $idprof]);
    header("Location: historial_reservas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Reservas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Historial de Reservas</h2>
        <a href="listado_profesores.php" class="btn btn-outline-primary mb-4">Volver al listado de libros</a>

        <?php if ($query->rowCount() > 0): ?>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Título del Libro</th>
                        <th>Fecha de Reserva</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reserva = $query->fetch()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reserva['Titulo']); ?></td>
                            <td><?php echo htmlspecialchars($reserva['Fecha']); ?></td>
                            <td>
                                <!-- Botón para cancelar la reserva -->
                                <a href="historial_reservas.php?cancelar=<?php echo $reserva['IdReserva']; ?>" 
                                   class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas cancelar esta reserva?')">Cancelar Reserva</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No tienes reservas en cola.</div>
        <?php endif; ?>
    </div>
</body>
</html>
