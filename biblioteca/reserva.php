<?php
require_once 'config.php';

if (isset($_GET['id']) && isset($_GET['idprof'])) {
    $idlibro = $_GET['id']; 
    $idprof = $_GET['idprof']; 

    // Verificar si el libro está en préstamo
    $sql = "SELECT * FROM Prestamo WHERE IdEjemplar = :idlibro AND FechaFin IS NULL";
    $query = $conexion->prepare($sql);
    $query->execute(['idlibro' => $idlibro]);

    if ($query->rowCount() > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Insertar reserva en la tabla 'Reserva'
            $sql_reserva = "INSERT INTO Reserva (IdEjemplar, IdProf, Fecha) VALUES (:idlibro, :idprof, NOW())";
            $query_reserva = $conexion->prepare($sql_reserva);
            $query_reserva->execute(['idlibro' => $idlibro, 'idprof' => $idprof]);

            $msg = "Te has puesto en cola de espera para este libro. Se te notificará cuando esté disponible.";
        } else {
            $msg = "El libro ya está en préstamo. ¿Deseas ponerte en cola de espera?";
        }
    } else {
        // El libro está disponible, registrar el préstamo
        $sql_prestamo = "INSERT INTO Prestamo (IdEjemplar, IdProf, FechaInicio) VALUES (:idlibro, :idprof, NOW())";
        $query_prestamo = $conexion->prepare($sql_prestamo);
        $query_prestamo->execute(['idlibro' => $idlibro, 'idprof' => $idprof]);

        $msg = "El préstamo del libro se ha realizado correctamente.";
    }
} else {
    die("Faltan parámetros necesarios.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Préstamo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-primary">Solicitud de Préstamo</h2>
        <p><?php echo isset($msg) ? $msg : ''; ?></p>

        <?php if (isset($msg) && strpos($msg, 'cola de espera') !== false): ?>
            <form method="POST">
                <button type="submit" class="btn btn-warning">Ponerse en cola de espera</button>
            </form>
        <?php endif; ?>

        <a href="listado_profesores.php" class="btn btn-outline-primary">Volver al listado de libros</a>
    </div>
</body>
</html>
