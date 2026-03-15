<?php
session_start();
require_once '../conexion.php';

// Control de Acceso
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = intval($_POST['id_cliente']);
    $id_habitacion = intval($_POST['id_habitacion']);
    $fecha_in = $_POST['fecha_entrada'];
    $fecha_out = $_POST['fecha_salida'];

    if(empty($id_cliente) || empty($id_habitacion) || empty($fecha_in) || empty($fecha_out)){
        $_SESSION['error_crud'] = "Todos los campos son obligatorios.";
        header("Location: ../../views/nueva_reserva.php");
        exit();
    }
    
    // Validación lógica de fechas
    if(strtotime($fecha_in) >= strtotime($fecha_out)){
        $_SESSION['error_crud'] = "La fecha de salida debe ser mayor a la fecha de entrada.";
        header("Location: ../../views/nueva_reserva.php");
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    $codigo = "RES-" . rand(1000, 9999);

    $query = "INSERT INTO reservaciones (codigo, id_cliente, id_habitacion, fecha_entrada, fecha_salida, estado) 
              VALUES (:codigo, :cliente, :habitacion, :in, :out, 'Pendiente')";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":codigo", $codigo);
    $stmt->bindParam(":cliente", $id_cliente);
    $stmt->bindParam(":habitacion", $id_habitacion);
    $stmt->bindParam(":in", $fecha_in);
    $stmt->bindParam(":out", $fecha_out);

    if($stmt->execute()){
        // Actualizar el estado de la habitación a ocupada temporalmente
        $updateHab = $db->prepare("UPDATE habitaciones SET estado = 'Ocupada' WHERE id_habitacion = :h_id");
        $updateHab->bindParam(':h_id', $id_habitacion);
        $updateHab->execute();
        
        $_SESSION['mensaje_crud'] = "Reserva '$codigo' guardada correctamente y habitación asignada.";
        header("Location: ../../views/reservaciones/reservaciones.php");
    } else {
        $_SESSION['error_crud'] = "Error al intentar registrar la reserva.";
        header("Location: ../../views/nueva_reserva.php");
    }
} else {
    header("Location: ../../views/reservaciones/reservaciones.php");
}
?>
