<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
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


    <link rel="stylesheet" href="../css/panel.css?v=<?php echo time(); ?>">

</head>

<body>

    <nav class="navbar">
        <a href="panel.php" class="logo-link" style="text-decoration:none;">
            <div class="logo">HOTEL</div>
        </a>

        <div class="nav-links">
            <span style="font-weight:600;margin-right:35px;">
                Bienvenido -
                <?php echo($rol_id == 1 ? 'Administrador' : 'Cliente'); ?> 
                <?php echo htmlspecialchars($nombre_usuario); ?>
            </span>

            <?php if ($rol_id == 1): ?>
                <a href="usuarios/usuarios.php">Usuarios</a>
            <?php
endif; ?>

            <a href="../php/auth/logout.php" class="btn btn-danger" style="margin-left:15px;padding:5px 10px;">
                Cerrar Sesión
            </a>

        </div>
    </nav>


    <div class="panel-grid">

        <!-- MITAD IZQUIERDA IMAGEN -->
        <div class="panel-left"></div>

        <!-- MITAD DERECHA CONTENIDO -->
        <div class="panel-right">
            <div class="panel-overlay"></div>
            <div class="panel-content">
                <h2 class="panel-title">Panel de Control</h2>
                <div class="grid-opciones">
                    <?php if ($rol_id == 1): ?>
                        <!-- 👥 -->
                        <a href="clientes/clientes.php" class="card card-admin">
                            <h3> Clientes</h3>
                            <p>Gestionar base de datos de clientes y estatus.</p>
                        </a>
                    <?php
endif; ?>
                    
                    <!-- 🛏️ -->
                    <a href="habitaciones/habitaciones.php" class="card <?php echo $rol_id == 1 ? 'card-admin' : 'card-user'; ?>">
                        <h3> Habitaciones</h3>
                        <p><?php echo $rol_id == 1 ? 'Ver y modificar estado de las habitaciones del hotel.' : 'Ver habitaciones disponibles.'; ?></p>
                    </a>
                    <!-- 📅 -->
                    <a href="reservaciones/reservaciones.php" class="card card-user">
                        <h3> Reservaciones</h3>
                        <p>Consulta las reservaciones activas e historial disponible.</p>
                    </a>

                </div>
            </div>
        </div>

    </div>

</body>

</html>