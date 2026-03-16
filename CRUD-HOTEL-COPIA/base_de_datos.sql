CREATE DATABASE IF NOT EXISTS crud_hotel_2;
USE crud_hotel_2;


-- Tablas y registros para usuarios y roles 
/* TABLA: roles
   Guardamos los tipos de usuario (Administrador, Cliente, etc.) */
CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
);

/* TABLA: usuarios
   Guardamos la informacion completa del usuario y credenciales 
   con medidas de seguridad (intentos_fallidos, tokens) */
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, /* Espacio para hash Bcrypt */
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

/* DATOS DE PRUEBA - ROLES */
INSERT INTO roles (nombre_rol) VALUES
('Administrador'),
('Cliente');

/* DATOS DE PRUEBA - USUARIOS
   Las contraseñas generadas aquí son '12345' en texto plano */
INSERT INTO usuarios (nombre_completo, correo, password, id_rol) VALUES
('Emir', 'dsm23190242.epolito@alumnos.utsv.edu.mx', '12345', 1),
('Irving', 'dsm23190360.imolina@alumnos.utsv.edu.mx', '12345', 1),
('Emir Polito', 'emirpolitog@gmail.com', '12345', 2);


-- Tablas y registros para el CRUD del hotel 
/* TABLA: clientes */
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

/* TABLA: habitaciones */
CREATE TABLE habitaciones (
    id_habitacion INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10) NOT NULL UNIQUE,
    tipo VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    estado ENUM('Disponible', 'Ocupada', 'Mantenimiento') DEFAULT 'Disponible'
);

/* TABLA: reservaciones */
CREATE TABLE reservaciones (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    id_cliente INT NOT NULL,
    id_habitacion INT NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    estado ENUM('Confirmada', 'Pendiente', 'Cancelada') DEFAULT 'Pendiente',
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
    FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id_habitacion) ON DELETE CASCADE
);

/* DATOS DE PRUEBA - CLIENTES */
INSERT INTO clientes (nombre_completo, telefono, estado) VALUES
('María García', '555-0192', 'Activo'),
('Carlos López', '555-3841', 'Activo'),
('Ana Martínez', '555-7734', 'Inactivo');

/* DATOS DE PRUEBA - HABITACIONES */
INSERT INTO habitaciones (numero, tipo, precio, estado) VALUES
('101A', 'Sencilla', 50.00, 'Disponible'),
('105B', 'Doble', 85.00, 'Ocupada'),
('201C', 'Suite', 150.00, 'Mantenimiento'),
('302A', 'Doble', 85.00, 'Disponible');

/* DATOS DE PRUEBA - RESERVACIONES */
INSERT INTO reservaciones (codigo, id_cliente, id_habitacion, fecha_entrada, fecha_salida, estado) VALUES
('RES-9912', 1, 2, '2026-03-20', '2026-03-25', 'Confirmada'),
('RES-9934', 2, 3, '2026-04-10', '2026-04-15', 'Pendiente');

SELECT * FROM roles;
SELECT * FROM usuarios;
SELECT * FROM clientes;
SELECT * FROM habitaciones;
SELECT * FROM reservaciones;
