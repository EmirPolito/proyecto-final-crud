<?php
// Inicia la sesión para acceder a variables como usuario, rol, etc.
session_start();

// Conexión a la base de datos
require_once '../../php/conexion.php';

// CONTROL DE ACCESO
// Verifica que el usuario esté logueado y que sea administrador (rol_id = 1)
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../login.php"); // Si no cumple, lo redirige al login
    exit();
}

//CONEXIÓN A LA BASE DE DATOS
$database = new Conexion();
$db = $database->obtenerConexion();


// CONSULTA PARA OBTENER USUARIOS
// Se obtienen todos los usuarios junto con su rol
$query = "SELECT u.id_usuario, u.nombre_completo, u.correo, r.nombre_rol 
          FROM usuarios u 
          JOIN roles r ON u.id_rol = r.id_rol 
          ORDER BY u.id_usuario DESC";
$stmt = $db->prepare($query); //Preparar la consulta.
$stmt->execute(); //Ejecutar la consulta
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);//Obtener todos los resultados como arreglo asociativo


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios - Administrador - CRUD HOTEL</title>
    <link rel="stylesheet" href="../../css/usuarios.css?v=<?php echo time(); ?>">
</head>

<body>
    <nav class="navbar">

        <a href="../panel.php" class="logo-link" style="text-decoration: none;">
            <div class="logo">HOTEL</div>
        </a>

        <div class="nav-links">
            <span style="font-weight: 600; margin-right: 310px;"> <!-- Mensaje de bienvenida con rol dinámico -->
                Bienvenido -
                <?php echo ($_SESSION['usuario_rol_id'] == 1 ? 'Administrador' : 'Cliente'); ?>
                <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
            </span>

            <a href="../panel.php">Inicio</a>
            <a href="../usuarios/usuarios.php">Usuarios</a>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="margin-left: 10px; padding: 5px 10px;">Cerrar Sesión</a>
            
        </div>
    </nav>


    <!-- /usuarios -->
    <div class="container crud-container">
        <div class="crud-header">
            <h2 class="ttl">Gestión de Usuarios</h2>
            <a href="nuevo_usuario.php" class="btn btn-primary">+ Nuevo Usuario</a>
        </div>

        <!-- MENSAJES DEL SISTEMA (Éxito/Error) -->
        <?php
        // Mensaje de éxito
        if (isset($_SESSION['mensaje_crud'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['mensaje_crud'] . "</div>";
            unset($_SESSION['mensaje_crud']); // Se elimina después de mostrar
        }

        // Mensaje de error
        if (isset($_SESSION['error_crud'])) {
            echo "<div class='alert alert-error'>" . $_SESSION['error_crud'] . "</div>";
            unset($_SESSION['error_crud']);
        }

        ?>
        <table class="tabla-crud">

            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($usuarios as $user): ?><!-- Recorre todos los usuarios -->
                    <tr>

                        <!-- Id -->
                        <td style="color: #c6c6c6;">
                            <?php echo $user['id_usuario']; ?>
                        </td>

                        <!-- Nombre Completo -->
                        <td style="color: #c6c6c6;">
                            <strong><?php echo htmlspecialchars($user['nombre_completo']); ?></strong>
                        </td>

                        <!-- Correo -->
                        <td style="color: #c6c6c6;">
                            <?php echo htmlspecialchars($user['correo']); ?>
                        </td>

                        <!-- Rol con estilo dinámico -->
                        <td style="color: #c6c6c6;">
                            <span class="badge 
                                <?php echo $user['nombre_rol'] == 'Administrador' ? 'badge-admin' : 'badge-user'; ?>">
                                <?php echo $user['nombre_rol']; ?>
                            </span>
                        </td>

                        
                        <!-- Botones -->
                        <!-- Editar -->
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $user['id_usuario']; ?>"
                                class="btn btn-secondary btn-small">
                                Editar
                            </a>

                            <!-- Evita que el usuario se autoelimine -->
                            <?php if ($user['id_usuario'] != $_SESSION['usuario_id']): ?>

                                <button onclick="confirmarEliminacion(<?php echo $user['id_usuario']; ?>)"
                                    class="btn btn-danger btn-small">
                                    Eliminar
                                </button>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- Si no hay usuarios -->
                <?php if (count($usuarios) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px;">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>


    <!-- Modal de confirmación de eliminación -->
    <?php include '../modal_eliminar.php'; ?>

    <!-- Script para confirmar eliminación -->
    <script>
        function confirmarEliminacion(id) {
            confirmarEliminarCustom(
                '../../php/usuarios/eliminar_usuario.php?id=' + id,
                'Esta acción no se puede deshacer.'
            );
        }
    </script>
    
</body>
</html>