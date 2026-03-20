<?php
// Inicia la sesión para poder acceder a variables de sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");// Si no hay sesión, redirige al login
    exit();
}

// Obtiene datos del usuario desde la sesión
$nombre_usuario = $_SESSION['usuario_nombre']; // Nombre del usuario
$rol_id = $_SESSION['usuario_rol_id']; // Rol del usuario (1 = admin, otro = cliente)

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <title>Panel Principal - CRUD HOTEL</title>

    <!-- Hoja de estilos con parámetro dinámico para evitar caché -->
    <link rel="stylesheet" href="../css/panel.css?v=<?php echo time(); ?>">
</head>

<body>

    <!-- NAVBAR PRINCIPAL -->
    <nav class="navbar">
        <a href="panel.php" class="logo-link" style="text-decoration:none;">
            <div class="logo">HOTEL</div>
        </a>

        <div class="nav-links">

            <span style="font-weight:600;margin-right:35px;">
                Bienvenido -
                <?php echo ($rol_id == 1 ? 'Administrador' : 'Cliente'); ?> <!-- Muestra el rol dependiendo del ID -->
                <?php echo htmlspecialchars($nombre_usuario); ?> <!-- Muestra el nombre de forma segura -->
            </span>

            <!-- Enlace visible SOLO para administrador -->
            <?php if ($rol_id == 1): ?>
                <a href="usuarios/usuarios.php">Usuarios</a>
            <?php endif; ?>

            <!-- Botón para cerrar sesión -->
            <a href="../php/auth/logout.php" class="btn btn-danger" style="margin-left:15px;padding:5px 10px;">
                Cerrar Sesión
            </a>

        </div>
    </nav>

    <!-- CONTENEDOR PRINCIPAL DEL PANEL -->
    <div class="panel-grid">
        <div class="panel-left"></div>
        <div class="panel-right">
            <div class="panel-overlay"></div>
            <div class="panel-content">
                <h2 class="panel-title">Panel de Control</h2>
                <div class="grid-opciones">

                    <!-- OPCIÓN SOLO PARA ADMIN: CLIENTES -->
                    <?php if ($rol_id == 1): ?>
                        <a href="clientes/clientes.php" class="card card-admin">
                            <h3> Clientes</h3>
                            <p>Gestionar base de datos de clientes y estatus.</p>
                        </a>
                    <?php endif; ?>

                    <!-- OPCIÓN: HABITACIONES (cambia según rol) -->
                    <a href="habitaciones/habitaciones.php"
                        class="card <?php echo $rol_id == 1 ? 'card-admin' : 'card-user'; ?>">
                        <h3> Habitaciones</h3>
                        <p>
                            <?php
                            echo $rol_id == 1
                                ? 'Ver y modificar estado de las habitaciones del hotel.'
                                : 'Ver habitaciones disponibles.';
                            ?>
                        </p>
                    </a>

                    <!-- OPCIÓN: RESERVACIONES (para todos) -->
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