-- Crear la tabla de Profesores
CREATE TABLE Profesor (
    IdProf INT AUTO_INCREMENT PRIMARY KEY,
    Apellido1 VARCHAR(50) NOT NULL,
    Apellido2 VARCHAR(50) NOT NULL,
    Nombre VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Foto VARCHAR(255),
    Rol TINYINT(1) NOT NULL, 
    Password VARCHAR(255) NOT NULL 
);


-- Crear la tabla de Libros
CREATE TABLE Libro (
    IdEjemplar INT AUTO_INCREMENT PRIMARY KEY,
    ISBN VARCHAR(20) NOT NULL UNIQUE,
    Titulo VARCHAR(255) NOT NULL,
    FechaPublicacion DATE NOT NULL,
    Editorial VARCHAR(100) NOT NULL,
    Descripcion TEXT,
    Precio DECIMAL(10, 2) NOT NULL,
    Portada VARCHAR(255)
);

-- Crear la tabla de Préstamos
CREATE TABLE Prestamo (
    IdPrestamo INT AUTO_INCREMENT PRIMARY KEY,
    IdEjemplar INT NOT NULL,
    IdProf INT NOT NULL,
    FechaInicio DATE NOT NULL,
    FechaFin DATE,
    FOREIGN KEY (IdEjemplar) REFERENCES Libro(IdEjemplar) ON DELETE CASCADE,
    FOREIGN KEY (IdProf) REFERENCES Profesor(IdProf) ON DELETE CASCADE
);

-- Crear la tabla de Reservas
CREATE TABLE Reserva (
    IdReserva INT AUTO_INCREMENT PRIMARY KEY,
    IdEjemplar INT NOT NULL,
    IdProf INT NOT NULL,
    Fecha DATE NOT NULL,
    FOREIGN KEY (IdEjemplar) REFERENCES Libro(IdEjemplar) ON DELETE CASCADE,
    FOREIGN KEY (IdProf) REFERENCES Profesor(IdProf) ON DELETE CASCADE
);
