<?php
session_start();
require_once '../../php/conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: habitaciones.php");
    exit();
}

$id_editar = intval($_GET['id']);

$database = new Conexion();
$db = $database->obtenerConexion();

$stmt = $db->prepare("SELECT * FROM habitaciones WHERE id_habitacion = :id");
$stmt->bindParam(':id', $id_editar);
$stmt->execute();
$habitacion = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$habitacion){
    header("Location: habitaciones.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Habitación - CRUD HOTEL</title>
    <link rel="stylesheet" href="../../css/global.css">
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">HOTEL</div>
        <div class="nav-links">
            <a href="../habitaciones/habitaciones.php">Volver a Habitaciones</a>
            <a href="../../php/auth/logout.php" class="btn btn-danger" style="padding: 5px 10px;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="login-container" style="max-width: 500px; margin-top: 40px; border-top: 4px solid #f39c12;">
        <a href="habitaciones.php" class="btn-close-card" title="Cancelar">&times;</a>
        <h2 style="margin-bottom: 25px;">Editar Habitación <span style="color:#f39c12;">#<?php echo $habitacion['numero']; ?></span></h2>

        <form action="../../php/habitaciones/actualizar_habitacion.php" method="POST">
            <input type="hidden" name="id_habitacion" value="<?php echo $habitacion['id_habitacion']; ?>">
            
            <div class="form-group">
                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($habitacion['numero']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <input type="text" id="tipo" name="tipo" value="<?php echo htmlspecialchars($habitacion['tipo']); ?>" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio por noche ($):</label>
                <input type="number" step="0.01" id="precio" name="precio" value="<?php echo $habitacion['precio']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="estado">Estado de la Habitación:</label>
                <select id="estado" name="estado" required>
                    <option value="Disponible" <?php echo ($habitacion['estado'] == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                    <option value="Ocupada" <?php echo ($habitacion['estado'] == 'Ocupada') ? 'selected' : ''; ?>>Ocupada</option>
                    <option value="Mantenimiento" <?php echo ($habitacion['estado'] == 'Mantenimiento') ? 'selected' : ''; ?>>Mantenimiento</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; background-color: #f39c12;">Actualizar Habitación</button>
        </form>
    </div>
</body>
</html>
