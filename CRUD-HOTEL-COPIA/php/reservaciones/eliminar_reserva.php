<?php
session_start();
require_once '../conexion.php';

// Control de Acceso
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.php");
    exit();
}

if(isset($_GET['id'])){
    $id_reserva = intval($_GET['id']);
    
    $database = new Conexion();
    $db = $database->obtenerConexion();

    // 1. Obtener de qué cuarto era para desocuparlo si es necesario
    $stmtHab = $db->prepare("SELECT id_habitacion FROM reservaciones WHERE id_reserva = :id");
    $stmtHab->bindParam(':id', $id_reserva);
    $stmtHab->execute();
    
    if($stmtHab->rowCount() > 0){
        $reserva = $stmtHab->fetch(PDO::FETCH_ASSOC);
        $id_hab = $reserva['id_habitacion'];
        
        // 2. Liberar cuarto
        $updateHab = $db->prepare("UPDATE habitaciones SET estado = 'Disponible' WHERE id_habitacion = :h_id");
        $updateHab->bindParam(':h_id', $id_hab);
        $updateHab->execute();

        // 3. Borrar reserva
        $stmt = $db->prepare("DELETE FROM reservaciones WHERE id_reserva = :id");
        $stmt->bindParam(':id', $id_reserva);
        
        if($stmt->execute()){
            $_SESSION['mensaje_crud'] = "Reserva eliminada y habitación liberada correctamente.";
        } else {
            $_SESSION['error_crud'] = "Error al eliminar la reserva.";
        }
    }
}

header("Location: ../../views/reservaciones/reservaciones.php");
exit();
?>
