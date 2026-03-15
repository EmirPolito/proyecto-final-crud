<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Cualquier usuario autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$database = new Conexion();
$db = $database->obtenerConexion();

// Join para traer nombres en vez de solo IDs
$query = "SELECT r.id_reserva, r.codigo, r.fecha_entrada, r.fecha_salida, r.estado, 
                 c.nombre_completo AS cliente, h.numero AS habitacion 
          FROM reservaciones r 
          JOIN clientes c ON r.id_cliente = c.id_cliente
          JOIN habitaciones h ON r.id_habitacion = h.id_habitacion
          ORDER BY r.fecha_entrada ASC";
$stmt = $db->query($query);
$reservaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservaciones - CRUD HOTEL</title>
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/reservaciones.css">
</head>
<body>

    <nav class="navbar">
        <a href="../panel.php" class="logo-link" style="text-decoration: none;">
            <div class="logo">🏨 CRUD-HOTEL</div>
        </a>
        <div class="nav-links">
            <span style="font-weight: 600; margin-right: 15px;">Bienvenido, <?php echo ($_SESSION['usuario_rol_id'] == 1 ? 'Admin' : 'Cliente'); ?> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
            <a href="../panel.php">Inicio</a>
            <?php if($_SESSION['usuario_rol_id'] == 1): ?>
                <a href="../usuarios/usuarios.php">Usuarios</a>
            <?php endif; ?>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="margin-left: 10px; padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container crud-container" style="border-top: 4px solid #f39c12;">
        <div class="crud-header">
            <h2>📅 Gestión de Reservaciones</h2>
            <a href="nueva_reserva.php" class="btn btn-success">+ Nueva Reserva</a>
        </div>

        <?php
        if(isset($_SESSION['mensaje_crud'])){
            echo "<div class='alert alert-success' style='margin-bottom:15px;'>" . $_SESSION['mensaje_crud'] . "</div>";
            unset($_SESSION['mensaje_crud']);
        }
        if(isset($_SESSION['error_crud'])){
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
                <?php foreach($reservaciones as $r): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($r['codigo']); ?></strong></td>
                    <td><?php echo htmlspecialchars($r['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($r['habitacion']); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($r['fecha_entrada'])); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($r['fecha_salida'])); ?></td>
                    <td>
                        <span class="badge <?php echo $r['estado'] == 'Confirmada' ? 'badge-active' : ($r['estado'] == 'Pendiente' ? 'badge-user' : 'badge-inactive'); ?>">
                            <?php echo $r['estado']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="editar_reserva.php?id=<?php echo $r['id_reserva']; ?>" class="btn btn-secondary btn-small">Editar</a>
                        <button onclick="confirmarEliminacion(<?php echo $r['id_reserva']; ?>)" class="btn btn-danger btn-small">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($reservaciones) === 0): ?>
                    <tr><td colspan="7" class="text-center" style="padding: 20px;">No hay reservaciones registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmarEliminacion(id) {
            if(confirm('¿Estás seguro de cancelar/eliminar esta reservación?')){
                window.location.href = '../../php/reservaciones/eliminar_reserva.php?id=' + id;
            }
        }
    </script>
</body>
</html>
