<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: usuarios.php");
    exit();
}

$id_editar = intval($_GET['id']);

$database = new Conexion();
$db = $database->obtenerConexion();

// Obtener datos del usuario
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
$stmt->bindParam(':id', $id_editar);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$usuario){
    header("Location: usuarios.php");
    exit();
}

// Obtener roles
$stmtRoles = $db->query("SELECT * FROM roles");
$roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - CRUD HOTEL</title>

    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">HOTEL</div>
        <div class="nav-links">
            <a href="../usuarios/usuarios.php">Volver a Usuarios</a>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="login-container" style="max-width: 500px; margin-top: 40px; border-top: 4px solid #3498db;">
        <a href="usuarios.php" class="btn-close-card" title="Cancelar">&times;</a>
        <h2 style="margin-bottom: 25px;">Editar Usuario <span style="color:#3498db;">#<?php echo $usuario['id_usuario']; ?></span></h2>

        <form action="../../php/usuarios/actualizar_usuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre_completo" value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Nueva Contraseña (Opcional):</label>
                <input type="text" id="password" name="password" placeholder="Dejar en blanco para no cambiar">
            </div>

            <div class="form-group">
                <label for="rol">Rol del Usuario:</label>
                <select id="rol" name="id_rol" required>
                    <?php foreach($roles as $rol): ?>
                        <option value="<?php echo $rol['id_rol']; ?>" <?php echo ($rol['id_rol'] == $usuario['id_rol']) ? 'selected' : ''; ?>>
                            <?php echo $rol['nombre_rol']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Actualizar Usuario</button>
        </form>
    </div>
</body>
</html>
