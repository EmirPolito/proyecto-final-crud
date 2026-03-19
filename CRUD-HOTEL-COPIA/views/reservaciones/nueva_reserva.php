<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Cualquier usuario
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.php");
    exit();
}

$database = new Conexion();
$db = $database->obtenerConexion();

// Obtener clientes activos
if ($_SESSION['usuario_rol_id'] == 1) {
    $stmtClientes = $db->query("SELECT * FROM clientes WHERE estado = 'Activo'");
    $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmtClientes = $db->prepare("SELECT * FROM clientes WHERE id_usuario = :id_usuario AND estado = 'Activo'");
    $stmtClientes->bindParam(':id_usuario', $_SESSION['usuario_id']);
    $stmtClientes->execute();
    $clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener habitaciones disponibles
$stmtHabitaciones = $db->query("SELECT * FROM habitaciones WHERE estado = 'Disponible'");
$habitaciones = $stmtHabitaciones->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Reserva - CRUD HOTEL</title>

    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">HOTEL</div>
        <div class="nav-links">
            <a href="../reservaciones/reservaciones.php">Volver a Reservaciones</a>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="login-container" style="max-width: 500px; margin: 10px auto; border-top: 4px solid #f39c12;">
        <a href="reservaciones.php" class="btn-close-card" title="Cancelar">&times;</a>
        <h2 style="margin-bottom: 25px;">Crear <span style="color:#f39c12;">Nueva Reserva</span></h2>

        <?php if(count($clientes) == 0): ?>
            <div class='alert alert-error'>No hay clientes Activos. Vaya a clientes primero.</div>
        <?php elseif(count($habitaciones) == 0): ?>
            <div class='alert alert-error'>No hay habitaciones Disponibles. Vaya a habitaciones.</div>
        <?php else: ?>

        <form action="../../php/reservaciones/guardar_reserva.php" method="POST">
            <div class="form-group" <?php echo $_SESSION['usuario_rol_id'] == 2 ? 'style="display:none;"' : ''; ?>>
                <label for="cliente">Cliente:</label>
                <select id="cliente" name="id_cliente" required>
                    <?php if($_SESSION['usuario_rol_id'] == 1): ?>
                        <option value="">Seleccione...</option>
                    <?php endif; ?>
                    <?php foreach($clientes as $c): ?>
                        <option value="<?php echo $c['id_cliente']; ?>" <?php echo ($_SESSION['usuario_rol_id'] == 2) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['nombre_completo']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="habitacion">Habitación:</label>
                <select id="habitacion" name="id_habitacion" required>
                    <option value="">Seleccione...</option>
                    <?php foreach($habitaciones as $h): ?>
                        <option value="<?php echo $h['id_habitacion']; ?>"><?php echo htmlspecialchars($h['numero']); ?> - $<?php echo $h['precio']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_entrada">Fecha de Entrada:</label>
                <input type="date" id="fecha_entrada" name="fecha_entrada" required>
            </div>

            <div class="form-group">
                <label for="fecha_salida">Fecha de Salida:</label>
                <input type="date" id="fecha_salida" name="fecha_salida" required>
            </div>
            
            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px;">Crear Reserva</button>
        </form>
        
        <?php endif; ?>
    </div>
</body>
</html>
