<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_reserva = intval($_POST['id_reserva']);
    $id_cliente = intval($_POST['id_cliente']);
    $id_habitacion = intval($_POST['id_habitacion']);
    $id_habitacion_previa = intval($_POST['id_habitacion_previa']);
    $fecha_in = $_POST['fecha_entrada'];
    $fecha_out = $_POST['fecha_salida'];
    $estado = $_POST['estado'];

    // Validar lógicas de negocio...
    if(strtotime($fecha_in) >= strtotime($fecha_out)){
        $_SESSION['error_crud'] = "La fecha de salida debe ser mayor a la fecha de entrada.";
        header("Location: ../../views/reservas/editar_reserva.php?id=" . $id_reserva);
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    $query = "UPDATE reservaciones 
              SET id_cliente = :cliente, id_habitacion = :habitacion, 
                  fecha_entrada = :in, fecha_salida = :out, estado = :estado
              WHERE id_reserva = :id";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":cliente", $id_cliente);
    $stmt->bindParam(":habitacion", $id_habitacion);
    $stmt->bindParam(":in", $fecha_in);
    $stmt->bindParam(":out", $fecha_out);
    $stmt->bindParam(":estado", $estado);
    $stmt->bindParam(":id", $id_reserva);

    if($stmt->execute()){
        // Si cambió la habitación, liberar la previa y ocupar la nueva
        if($id_habitacion != $id_habitacion_previa){
            $db->query("UPDATE habitaciones SET estado = 'Disponible' WHERE id_habitacion = $id_habitacion_previa");
            if($estado != 'Cancelada'){
                $db->query("UPDATE habitaciones SET estado = 'Ocupada' WHERE id_habitacion = $id_habitacion");
            }
        } else {
            // Si la misma habitación cambió de estado la reserva a cancelada, liberarla
            if($estado == 'Cancelada'){
                $db->query("UPDATE habitaciones SET estado = 'Disponible' WHERE id_habitacion = $id_habitacion");
            } else if($estado == 'Confirmada' || $estado == 'Pendiente') {
                $db->query("UPDATE habitaciones SET estado = 'Ocupada' WHERE id_habitacion = $id_habitacion");
            }
        }
        
        $_SESSION['mensaje_crud'] = "Reserva actualizada correctamente.";
        header("Location: ../../views/reservaciones/reservaciones.php");
    } else {
        $_SESSION['error_crud'] = "Error al intentar actualizar la reserva.";
        header("Location: ../../views/reservas/editar_reserva.php?id=" . $id_reserva);
    }
} else {
    header("Location: ../../views/reservaciones/reservaciones.php");
}
?>
