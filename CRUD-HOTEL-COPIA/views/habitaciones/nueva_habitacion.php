<?php
session_start();
// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Habitación - CRUD HOTEL</title>
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">HOTEL</div>
        <div class="nav-links">
            <a href="../habitaciones/habitaciones.php">Volver a Habitaciones</a>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="login-container" style="max-width: 500px; margin-top: 40px; border-top: 4px solid #9b59b6;">
        <a href="habitaciones.php" class="btn-close-card" title="Cancelar">&times;</a>
        <h2 style="margin-bottom: 25px;">Registrar <span style="color:#9b59b6;">Habitación</span></h2>

        <form action="../../php/habitaciones/guardar_habitacion.php" method="POST">
            <div class="form-group">
                <label for="numero">Número (Ej. 101A):</label>
                <input type="text" id="numero" name="numero" required>
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo (Sencilla, Doble, Suite...):</label>
                <input type="text" id="tipo" name="tipo" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio por noche ($):</label>
                <input type="number" step="0.01" id="precio" name="precio" required>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado Inicial:</label>
                <select id="estado" name="estado" required>
                    <option value="Disponible">Disponible</option>
                    <option value="Ocupada">Ocupada</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; background-color:#9b59b6;">Guardar Habitación</button>
        </form>
    </div>
</body>
</html>
