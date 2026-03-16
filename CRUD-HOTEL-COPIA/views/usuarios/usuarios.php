<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: index.php");
    exit();
}

$database = new Conexion();
$db = $database->obtenerConexion();

// Obtener todos los usuarios con su respectivo rol
$query = "SELECT u.id_usuario, u.nombre_completo, u.correo, r.nombre_rol 
          FROM usuarios u 
          JOIN roles r ON u.id_rol = r.id_rol 
          ORDER BY u.id_usuario DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios - Administrador - CRUD HOTEL</title>
    <link rel="stylesheet" href="../../css/global.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../css/usuarios.css?v=<?php echo time(); ?>">

</head>

<body>

    <nav class="navbar">
        <a href="../panel.php" class="logo-link" style="text-decoration: none;">
            <div class="logo">HOTEL</div>
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

    <div class="container crud-container">
        <div class="crud-header">
            <h2 class="ttl">Gestión de Usuarios</h2>
            <a href="nuevo_usuario.php" class="btn btn-primary">+ Nuevo Usuario</a>
        </div>

        <?php
        if (isset($_SESSION['mensaje_crud'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['mensaje_crud'] . "</div>";
            unset($_SESSION['mensaje_crud']);
        }
        if (isset($_SESSION['error_crud'])) {
            echo "<div class='alert alert-error'>" . $_SESSION['error_crud'] . "</div>";
            unset($_SESSION['error_crud']);
        }
        ?>

        <table class="tabla-crud">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td style="color: #c6c6c6;"><?php echo $user['id_usuario']; ?></td>
                        <td style="color: #c6c6c6;"><strong><?php echo htmlspecialchars($user['nombre_completo']); ?></strong></td>
                        <td style="color: #c6c6c6;"><?php echo htmlspecialchars($user['correo']); ?></td>
                        <td>
                            <span style="color: #c6c6c6;" class="badge <?php echo $user['nombre_rol'] == 'Administrador' ? 'badge-admin' : 'badge-user'; ?>">
                                <?php echo $user['nombre_rol']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $user['id_usuario']; ?>" class="btn btn-secondary btn-small">Editar</a>
                            <?php if ($user['id_usuario'] != $_SESSION['usuario_id']): ?>
                                <button onclick="confirmarEliminacion(<?php echo $user['id_usuario']; ?>)" class="btn btn-danger btn-small">Eliminar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($usuarios) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px;">No hay usuarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmarEliminacion(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
                window.location.href = '../../php/usuarios/eliminar_usuario.php?id=' + id;
            }
        }
    </script>
</body>

</html>