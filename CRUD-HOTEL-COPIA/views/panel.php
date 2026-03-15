<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}
$nombre_usuario = $_SESSION['usuario_nombre'];
$rol_id = $_SESSION['usuario_rol_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - CRUD HOTEL</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/panel.css">
</head>
<body>

    <nav class="navbar">
        <a href="panel.php" class="logo-link" style="text-decoration: none;">
            <div class="logo">🏨 CRUD-HOTEL</div>
        </a>
        <div class="nav-links">
            <span style="font-weight: 600; margin-right: 15px;">Bienvenido, <?php echo ($rol_id == 1 ? 'Admin' : 'Cliente'); ?> <?php echo htmlspecialchars($nombre_usuario); ?></span>
            <a href="panel.php">Inicio</a>
            <?php if($rol_id == 1): ?>
                <a href="usuarios.php">Usuarios</a>
            <?php endif; ?>
            <a href="../php/auth/logout.php" class="btn btn-danger" style="margin-left: 10px; padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container" style="flex: 1; margin-top:20px; max-width: 1200px;">
        <div class="panel-grid">
            <!-- Columna Izquierda: Imagen -->
            <div class="panel-image-container">
                <div class="panel-image-placeholder">
                    <p>[Pon tu imagen aquí]</p>
                    <p style="font-size: 12px; margin-top: 10px; color: #bdc3c7;">
                        Puede ser una etiqueta &lt;img src="ruta" alt=""&gt; cubriendo el contenedor.
                    </p>
                </div>
            </div>

            <!-- Columna Derecha: Opciones y Grid -->
            <div class="panel-options">
                <div class="dashboard-header" style="border-bottom: none; margin-bottom: 20px;">
                    <h2 style="font-size: 28px; color: #2c3e50;">Panel de Control</h2>
                </div>

                <div class="grid-opciones">
                    <?php if($rol_id == 1): ?>
                    <a href="clientes.php" class="card card-admin">
                        <h3>👥 Clientes</h3>
                        <p>Gestionar base de datos de clientes y estatus.</p>
                    </a>

                    <a href="habitaciones.php" class="card card-admin">
                        <h3>🛏️ Habitaciones</h3>
                        <p>Ver y modificar estado de las habitaciones del hotel.</p>
                    </a>
                    <?php endif; ?>

                    <a href="reservaciones.php" class="card card-user">
                        <h3>📅 Reservaciones</h3>
                        <p>Consulta las reservaciones activas e historial disponible.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
