<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

$database = new Conexion();
$db = $database->obtenerConexion();

$stmt = $db->query("SELECT * FROM clientes ORDER BY id_cliente DESC");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes - CRUD HOTEL</title>

<link rel="stylesheet" href="../../css/clientes.css?v=<?php echo time(); ?>">


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
            <h2>Gestión de Clientes</h2>
            <a href="nuevo_cliente.php" class="btn btn-primary">+ Nuevo Cliente</a>
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
                    <th>ID Cliente</th>
                    <th>Nombre Completo</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td style="color: #c6c6c6;"><?php echo $c['id_cliente']; ?></td>
                        <td style="color: #c6c6c6;" ><strong><?php echo htmlspecialchars($c['nombre_completo']); ?></strong></td>
                        <td style="color: #c6c6c6;"><?php echo htmlspecialchars($c['telefono']); ?></td>
                        <td>
                            <span style="color: #c6c6c6;" class="badge <?php echo $c['estado'] == 'Activo' ? 'badge-active' : 'badge-inactive'; ?>">
                                <?php echo $c['estado']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="editar_cliente.php?id=<?php echo $c['id_cliente']; ?>" class="btn btn-secondary btn-small">Editar</a>
                            <button onclick="confirmarEliminacion(<?php echo $c['id_cliente']; ?>)" class="btn btn-danger btn-small">Eliminar</button>
                        </td>
                    </tr>
                <?php
endforeach; ?>
                <?php if (count($clientes) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px;">No hay clientes registrados.</td>
                    </tr>
                <?php
endif; ?>
            </tbody>
        </table>
    </div>
    <?php include '../modal_eliminar.php'; ?>
    <script>
        function confirmarEliminacion(id) {
            confirmarEliminarCustom('../../php/clientes/eliminar_cliente.php?id=' + id, 'Se eliminarán también sus reservaciones.');
        }
    </script>
</body>

</html>