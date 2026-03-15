<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: index.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: clientes.php");
    exit();
}

$id_editar = intval($_GET['id']);

$database = new Conexion();
$db = $database->obtenerConexion();

$stmt = $db->prepare("SELECT * FROM clientes WHERE id_cliente = :id");
$stmt->bindParam(':id', $id_editar);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$cliente){
    header("Location: clientes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - CRUD HOTEL</title>
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">🏨 CRUD-HOTEL</div>
        <div class="nav-links">
            <a href="../clientes/clientes.php">Volver a Clientes</a>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="login-container" style="max-width: 500px; margin-top: 40px; border-top: 4px solid #f39c12;">
        <h2 style="margin-bottom: 25px;">Editar Cliente <span style="color:#f39c12;">#<?php echo $cliente['id_cliente']; ?></span></h2>

        <form action="../../php/clientes/actualizar_cliente.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre_completo" value="<?php echo htmlspecialchars($cliente['nombre_completo']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado del Cliente:</label>
                <select id="estado" name="estado" required>
                    <option value="Activo" <?php echo ($cliente['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="Inactivo" <?php echo ($cliente['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; background-color: #f39c12;">Actualizar Cliente</button>
        </form>
    </div>
</body>
</html>
