<?php
session_start();
require_once '../conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../login.php");
    exit();
}

if(isset($_GET['id'])){
    $id_eliminar = intval($_GET['id']);

    // Prevenir auto-eliminación
    if($id_eliminar == $_SESSION['usuario_id']){
        $_SESSION['error_crud'] = "No puedes eliminar tu propia cuenta actualmente en uso.";
        header("Location: ../../views/usuarios/usuarios.php");
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    $stmt = $db->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
    $stmt->bindParam(':id', $id_eliminar);

    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Usuario eliminado correctamente.";
    } else {
        $_SESSION['error_crud'] = "Ocurrió un error al intentar eliminar el usuario.";
    }
}

header("Location: ../../views/usuarios/usuarios.php");
exit();
?>
