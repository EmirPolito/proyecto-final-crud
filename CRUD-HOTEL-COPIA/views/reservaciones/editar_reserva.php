<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Cualquier usuario
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: reservaciones.php");
    exit();
}

$id_editar = intval($_GET['id']);
$database = new Conexion();
$db = $database->obtenerConexion();

// Obtener la reservación actual
$stmt = $db->prepare("SELECT * FROM reservaciones WHERE id_reserva = :id");
$stmt->bindParam(':id', $id_editar);
$stmt->execute();
$reserva = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$reserva){
    header("Location: reservaciones.php");
    exit();
}

// Obtener listas
$clientes = $db->query("SELECT * FROM clientes WHERE estado = 'Activo' OR id_cliente = {$reserva['id_cliente']}")->fetchAll(PDO::FETCH_ASSOC);
$habitaciones = $db->query("SELECT * FROM habitaciones WHERE estado = 'Disponible' OR id_habitacion = {$reserva['id_habitacion']}")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reserva - CRUD HOTEL</title>

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

    <div class="login-container" style="max-width: 500px; margin-top: 40px; border-top: 4px solid #f39c12;">
        <a href="reservaciones.php" class="btn-close-card" title="Cancelar">&times;</a>
        <h2 style="margin-bottom: 25px;">Editar Reserva <span style="color:#f39c12;">#<?php echo $reserva['codigo']; ?></span></h2>

        <form action="../../php/reservaciones/actualizar_reserva.php" method="POST">
            <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
            <input type="hidden" name="id_habitacion_previa" value="<?php echo $reserva['id_habitacion']; ?>">
            
            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <select id="cliente" name="id_cliente" required>
                    <?php foreach($clientes as $c): ?>
                        <option value="<?php echo $c['id_cliente']; ?>" <?php echo ($c['id_cliente'] == $reserva['id_cliente']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="habitacion">Habitación:</label>
                <select id="habitacion" name="id_habitacion" required>
                    <?php foreach($habitaciones as $h): ?>
                        <option value="<?php echo $h['id_habitacion']; ?>" <?php echo ($h['id_habitacion'] == $reserva['id_habitacion']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($h['numero']); ?> - $<?php echo $h['precio']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha_entrada">Fecha de Entrada:</label>
                <input type="date" id="fecha_entrada" name="fecha_entrada" value="<?php echo $reserva['fecha_entrada']; ?>" required>
            </div>

            <div class="form-group">
                <label for="fecha_salida">Fecha de Salida:</label>
                <input type="date" id="fecha_salida" name="fecha_salida" value="<?php echo $reserva['fecha_salida']; ?>" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado de Reserva:</label>
                <select id="estado" name="estado" required>
                    <option value="Pendiente" <?php echo ($reserva['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Confirmada" <?php echo ($reserva['estado'] == 'Confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                    <option value="Cancelada" <?php echo ($reserva['estado'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; background-color: #e67e22;">Actualizar Reserva</button>
        </form>
    </div>
</body>
</html>
