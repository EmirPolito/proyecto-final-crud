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

    // 1. Obtener de qué cuarto era para desocuparlo
    $stmtHab = $db->prepare("SELECT id_habitacion, estado FROM reservaciones WHERE id_reserva = :id");
    $stmtHab->bindParam(':id', $id_reserva);
    $stmtHab->execute();
    
    if($stmtHab->rowCount() > 0){
        $reserva = $stmtHab->fetch(PDO::FETCH_ASSOC);
        
        // Solo cancelar si la reserva NO está ya cancelada o completada
        if($reserva['estado'] == 'Pendiente' || $reserva['estado'] == 'Confirmada') {
            $id_hab = $reserva['id_habitacion'];
            
            // 2. Liberar cuarto
            $updateHab = $db->prepare("UPDATE habitaciones SET estado = 'Disponible' WHERE id_habitacion = :h_id");
            $updateHab->bindParam(':h_id', $id_hab);
            $updateHab->execute();

            // 3. Cambiar el estado de la reserva a Cancelada
            $stmt = $db->prepare("UPDATE reservaciones SET estado = 'Cancelada' WHERE id_reserva = :id");
            $stmt->bindParam(':id', $id_reserva);
            
            if($stmt->execute()){
                $_SESSION['mensaje_crud'] = "Reserva cancelada exitosamente y la habitación ha sido liberada.";
            } else {
                $_SESSION['error_crud'] = "Error al intentar cancelar la reserva.";
            }
        } else {
            $_SESSION['error_crud'] = "La reserva ya está cancelada o no se puede cancelar.";
        }
    }
}

header("Location: ../../views/reservaciones/reservaciones.php");
exit();
?>
