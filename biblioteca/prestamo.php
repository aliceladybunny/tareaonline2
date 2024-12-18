<?php
require_once 'config.php';

$msgresultado = "";

session_start(); 

$idprof = $_SESSION['idprof'];
$idlibro = $_GET['id']; 

$sql = "SELECT * FROM Libro WHERE IdEjemplar = :idlibro";
$query = $conexion->prepare($sql);
$query->execute(['idlibro' => $idlibro]);
$libro = $query->fetch();

if (!$libro) {
    die("El libro solicitado no existe.");
}

$sql_prestamo = "SELECT * FROM Prestamo WHERE IdEjemplar = :idlibro AND FechaFin IS NULL";
$query_prestamo = $conexion->prepare($sql_prestamo);
$query_prestamo->execute(['idlibro' => $idlibro]);

if ($query_prestamo->rowCount() > 0) {
    if ($_POST) {

        // Insertar la reserva en la tabla Reserva
        $sql_reserva = "INSERT INTO Reserva (IdEjemplar, IdProf, Fecha) VALUES (:idlibro, :idprof, NOW())";
        $query_reserva = $conexion->prepare($sql_reserva);
        $query_reserva->execute(['idlibro' => $idlibro, 'idprof' => $idprof]);
        
        // Enviar un correo al profesor que tiene el libro
        $sql_profesor = "SELECT * FROM Profesor WHERE IdProf = (SELECT IdProf FROM Prestamo WHERE IdEjemplar = :idlibro AND FechaFin IS NULL)";
        $query_profesor = $conexion->prepare($sql_profesor);
        $query_profesor->execute(['idlibro' => $idlibro]);
        $profesor = $query_profesor->fetch();

        $to = $profesor['Email'];
        $subject = "Aviso de solicitud de libro";
        $message = "Estimado profesor, hay un profesor esperando el libro: " . $libro['Titulo'] . ". Por favor, devuélvalo si ya no lo está utilizando.";
        mail($to, $subject, $message);

        $msgresultado = "Te has puesto en cola de espera para este libro. Se te notificará cuando esté disponible.";
    }
} else {
    // El libro está disponible, registrar el préstamo
    $sql_prestamo = "INSERT INTO Prestamo (IdEjemplar, IdProf, FechaInicio) VALUES (:idlibro, :idprof, NOW())";
    $query_prestamo = $conexion->prepare($sql_prestamo);
    $query_prestamo->execute(['idlibro' => $idlibro, 'idprof' => $idprof]);

    $msgresultado = "El préstamo del libro " . $libro['Titulo'] . " se ha realizado correctamente.";
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
        <p><?php echo $msgresultado; ?></p>
        
        <?php if ($query_prestamo->rowCount() > 0): ?>
            <form method="POST">
                <button type="submit" class="btn btn-warning">Ponerse en cola de espera</button>
            </form>
        <?php endif; ?>

        <a href="listado_profesores.php" class="btn btn-outline-primary">Volver al listado de libros</a>
    </div>
</body>
</html>
