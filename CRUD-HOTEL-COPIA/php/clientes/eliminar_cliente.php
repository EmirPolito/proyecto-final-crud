<?php
session_start();
require_once '../conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if(isset($_GET['id'])){
    $id_cliente = intval($_GET['id']);
    
    $database = new Conexion();
    $db = $database->obtenerConexion();

    // Las reservaciones se borran en cascada según la BD (ON DELETE CASCADE)
    $stmt = $db->prepare("DELETE FROM clientes WHERE id_cliente = :id");
    $stmt->bindParam(':id', $id_cliente);
    
    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Cliente (y sus reservaciones) eliminado correctamente.";
    } else {
        $_SESSION['error_crud'] = "Error al eliminar el cliente.";
    }
}

header("Location: ../../views/clientes/clientes.php");
exit();
?>
