<?php
session_start();
require_once '../conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if(isset($_GET['id'])){
    $id_habitacion = intval($_GET['id']);
    
    $database = new Conexion();
    $db = $database->obtenerConexion();

    // ON DELETE CASCADE eliminará las reservaciones atadas
    $stmt = $db->prepare("DELETE FROM habitaciones WHERE id_habitacion = :id");
    $stmt->bindParam(':id', $id_habitacion);
    
    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Habitación (y sus reservaciones) eliminada correctamente.";
    } else {
        $_SESSION['error_crud'] = "Error al eliminar la habitación.";
    }
}

header("Location: ../../views/habitaciones/habitaciones.php");
exit();
?>
