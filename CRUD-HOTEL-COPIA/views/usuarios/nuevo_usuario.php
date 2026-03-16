<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: index.php");
    exit();
}

// Obtener roles para el select
$database = new Conexion();
$db = $database->obtenerConexion();
$stmt = $db->query("SELECT * FROM roles");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario - CRUD HOTEL</title>
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/registro.css">
</head>
<body>
    <nav class="navbar">
        <a href="../panel.php" class="logo-link" style="text-decoration: none;">
            <div class="logo">🏨 CRUD-HOTEL</div>
        </a>
        <div class="nav-links">
            <span style="font-weight: 600; margin-right: 15px;">
                Bienvenido 
                <?php echo ($_SESSION['usuario_rol_id'] == 1 ? 'Administrador' : 'Cliente'); ?> 
                <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
            </span>
            <a href="../panel.php">Inicio</a>
            <a href="../usuarios/usuarios.php">Usuarios</a>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="margin-left: 10px; padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="login-container" style="max-width: 500px; border-top: 4px solid #2ecc71;">
        <a href="usuarios.php" class="btn-close-card" title="Cancelar">&times;</a>
        <h2>Registrar <span style="color:#2ecc71;">Nuevo Usuario</span></h2>

        <form action="../../php/usuarios/guardar_usuario.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre_completo" required>
            </div>
            
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña temporal:</label>
                <input type="text" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="rol">Rol del Usuario:</label>
                <select id="rol" name="id_rol" required>
                    <option value="">Seleccione un rol...</option>
                    <?php foreach($roles as $rol): ?>
                        <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['nombre_rol']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px;">Guardar Usuario</button>
        </form>
    </div>
</body>
</html>
