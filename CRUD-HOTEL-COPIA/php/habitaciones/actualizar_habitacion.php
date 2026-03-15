<?php
session_start();
require_once '../conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_habitacion = intval($_POST['id_habitacion']);
    $numero = trim($_POST['numero']);
    $tipo = trim($_POST['tipo']);
    $precio = floatval($_POST['precio']);
    $estado = $_POST['estado'];

    if(empty($numero) || empty($tipo) || $precio <= 0){
        $_SESSION['error_crud'] = "Datos inválidos o campos vacíos.";
        header("Location: ../../views/habitacions/editar_habitacion.php?id=" . $id_habitacion);
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    // Verificamos si el número de cuarto ya existe en OTRA habitación
    $stmtCheck = $db->prepare("SELECT id_habitacion FROM habitaciones WHERE numero = :num AND id_habitacion != :id");
    $stmtCheck->bindParam(':num', $numero);
    $stmtCheck->bindParam(':id', $id_habitacion);
    $stmtCheck->execute();
    
    if($stmtCheck->rowCount() > 0){
        $_SESSION['error_crud'] = "El número de habitación ya está en uso por otra habitación.";
        header("Location: ../../views/habitacions/editar_habitacion.php?id=" . $id_habitacion);
        exit();
    }

    $query = "UPDATE habitaciones SET numero = :numero, tipo = :tipo, precio = :precio, estado = :estado WHERE id_habitacion = :id";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":numero", $numero);
    $stmt->bindParam(":tipo", $tipo);
    $stmt->bindParam(":precio", $precio);
    $stmt->bindParam(":estado", $estado);
    $stmt->bindParam(":id", $id_habitacion);

    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Habitación actualizada correctamente.";
        header("Location: ../../views/habitaciones/habitaciones.php");
    } else {
        $_SESSION['error_crud'] = "Error al intentar actualizar la habitación.";
        header("Location: ../../views/habitacions/editar_habitacion.php?id=" . $id_habitacion);
    }
} else {
    header("Location: ../../views/habitaciones/habitaciones.php");
}
?>
