<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Usuarios logueados
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../index.php");
    exit();
}

$database = new Conexion();
$db = $database->obtenerConexion();

if ($_SESSION['usuario_rol_id'] == 1) {
    $stmt = $db->query("SELECT * FROM habitaciones ORDER BY id_habitacion DESC");
}
else {
    $stmt = $db->query("SELECT * FROM habitaciones WHERE estado = 'Disponible' ORDER BY id_habitacion DESC");
}
$habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

function obternerClaseEstado($estado)
{
    if ($estado == 'Disponible')
        return 'badge-active';
    if ($estado == 'Ocupada')
        return 'badge-inactive';
    return 'badge-user'; // Para Mantenimiento u otros
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Habitaciones - CRUD HOTEL</title>


    <link rel="stylesheet" href="../../css/habitaciones.css?v=<?php echo time(); ?>">

</head>

<body>

    <nav class="navbar">
        <a href="../panel.php" class="logo-link" style="text-decoration: none;">
            <div class="logo">HOTEL</div>
        </a>
        <div class="nav-links">
            <span style="font-weight: 600; margin-right: 390px;">
                Bienvenido -
                <?php echo($_SESSION['usuario_rol_id'] == 1 ? 'Administrador' : 'Cliente'); ?>
                <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
            </span>
            <a href="../panel.php">Inicio</a>
            <?php if ($_SESSION['usuario_rol_id'] == 1): ?>
                <a href="../usuarios/usuarios.php">Usuarios</a>
            <?php
endif; ?>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="margin-left: 10px; padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container crud-container">
        <div class="crud-header">
            <h2>Gestión de Habitaciones</h2>
            <?php if ($_SESSION['usuario_rol_id'] == 1): ?>
                <a href="nueva_habitacion.php" class="btn btn-primary">+ Añadir Habitación</a>
            <?php
endif; ?>
        </div>

        <?php
if (isset($_SESSION['mensaje_crud'])) {
    echo "<div class='alert alert-success' style='margin-bottom:15px;'>" . $_SESSION['mensaje_crud'] . "</div>";
    unset($_SESSION['mensaje_crud']);
}
if (isset($_SESSION['error_crud'])) {
    echo "<div class='alert alert-error' style='margin-bottom:15px;'>" . $_SESSION['error_crud'] . "</div>";
    unset($_SESSION['error_crud']);
}
?>

        <table class="tabla-crud">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Tipo</th>
                    <th>Precio x Noche</th>
                    <th>Estado</th>
                    <?php if ($_SESSION['usuario_rol_id'] == 1): ?>
                        <th>Acción</th>
                    <?php
endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($habitaciones as $h): ?>
                    <tr>
                        <td style="color: #c6c6c6;"><strong><?php echo htmlspecialchars($h['numero']); ?></strong></td>
                        <td style="color: #c6c6c6;"><?php echo htmlspecialchars($h['tipo']); ?></td>
                        <td style="color: #c6c6c6;">$<?php echo number_format($h['precio'], 2); ?></td>
                        <td>
                            <span style="color: #c6c6c6;" class="badge <?php echo obternerClaseEstado($h['estado']); ?>">
                                <?php echo $h['estado']; ?>
                            </span>
                        </td>
                        <?php if ($_SESSION['usuario_rol_id'] == 1): ?>
                        <td>
                            <a href="editar_habitacion.php?id=<?php echo $h['id_habitacion']; ?>" class="btn btn-secondary btn-small">Editar</a>
                            <button onclick="confirmarEliminacion(<?php echo $h['id_habitacion']; ?>)" class="btn btn-danger btn-small">Eliminar</button>
                        </td>
                        <?php
    endif; ?>
                    </tr>
                <?php
endforeach; ?>
                <?php if (count($habitaciones) === 0): ?>
                    <tr>
                        <td colspan="<?php echo $_SESSION['usuario_rol_id'] == 1 ? '5' : '4'; ?>" class="text-center" style="padding: 20px;">No hay habitaciones para mostrar.</td>
                    </tr>
                <?php
endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../modal_eliminar.php'; ?>
    <script>
        function confirmarEliminacion(id) {
            confirmarEliminarCustom('../../php/habitaciones/eliminar_habitacion.php?id=' + id, 'Se eliminarán también las reservaciones asociadas a ella.');
        }
    </script>
</body>

</html>