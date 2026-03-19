<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Cualquier usuario autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.php");
    exit();
}

$database = new Conexion();
$db = $database->obtenerConexion();

if ($_SESSION['usuario_rol_id'] == 1) {
    $query = "SELECT r.id_reserva, r.codigo, r.fecha_entrada, r.fecha_salida, r.estado, 
                     c.nombre_completo AS cliente, h.numero AS habitacion 
              FROM reservaciones r 
              JOIN clientes c ON r.id_cliente = c.id_cliente
              JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
              ORDER BY r.fecha_entrada ASC";
    $stmt = $db->query($query);
}
else {
    $query = "SELECT r.id_reserva, r.codigo, r.fecha_entrada, r.fecha_salida, r.estado, 
                     c.nombre_completo AS cliente, h.numero AS habitacion 
              FROM reservaciones r 
              JOIN clientes c ON r.id_cliente = c.id_cliente
              JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
              WHERE c.id_usuario = :id_usuario
              ORDER BY r.fecha_entrada ASC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_usuario', $_SESSION['usuario_id']);
    $stmt->execute();
}
$reservaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservaciones - CRUD HOTEL</title>

    <link rel="stylesheet" href="../../css/reservaciones.css?v=<?php echo time(); ?>">

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
            <h2>Gestión de Reservaciones</h2>
            <a href="nueva_reserva.php" class="btn btn-primary">+ Nueva Reserva</a>
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
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Habitación</th>
                    <th>Ingreso</th>
                    <th>Salida</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservaciones as $r): ?>
                <tr>
                    <td style="color: #c6c6c6;"><strong><?php echo htmlspecialchars($r['codigo']); ?></strong></td>
                    <td style="color: #c6c6c6;"><?php echo htmlspecialchars($r['cliente']); ?></td>
                    <td style="color: #c6c6c6;"><?php echo htmlspecialchars($r['habitacion']); ?></td>
                    <td style="color: #c6c6c6;"><?php echo date("d/m/Y", strtotime($r['fecha_entrada'])); ?></td>
                    <td style="color: #c6c6c6;"><?php echo date("d/m/Y", strtotime($r['fecha_salida'])); ?></td>
                    <td>
                        <span style="color: #c6c6c6;" class="badge <?php echo $r['estado'] == 'Confirmada' ? 'badge-active' : ($r['estado'] == 'Pendiente' ? 'badge-user' : 'badge-inactive'); ?>">
                            <?php echo $r['estado']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($_SESSION['usuario_rol_id'] == 1): ?>
                            <a href="editar_reserva.php?id=<?php echo $r['id_reserva']; ?>" class="btn btn-secondary btn-small">Editar</a>
                            <button onclick="confirmarEliminacion(<?php echo $r['id_reserva']; ?>)" class="btn btn-danger btn-small">Eliminar</button>
                        <?php
    else: ?>
                            <?php if ($r['estado'] == 'Pendiente'): ?>
                                <button onclick="confirmarCancelacion(<?php echo $r['id_reserva']; ?>)" class="btn btn-danger btn-small">Cancelar</button>
                            <?php
        else: ?>
                                <span class="text-muted" style="font-size: 0.9em; color: #888;">No cancelable</span>
                            <?php
        endif; ?>
                        <?php
    endif; ?>
                    </td>
                </tr>
                <?php
endforeach; ?>
                <?php if (count($reservaciones) === 0): ?>
                    <tr style="color: #c6c6c6;"><td colspan="7" class="text-center" style="padding: 20px;">No hay reservaciones registradas.</td></tr>
                <?php
endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../modal_eliminar.php'; ?>
    <script>
        function confirmarEliminacion(id) {
            confirmarEliminarCustom('../../php/reservaciones/eliminar_reserva.php?id=' + id, '¿Estás seguro de eliminar completamente esta reservación de la base de datos?');
        }
        function confirmarCancelacion(id) {
            confirmarEliminarCustom('../../php/reservaciones/cancelar_reserva.php?id=' + id, '¿Estás seguro de que quieres cancelar tu reservación?');
        }
    </script>
</body>
</html>
