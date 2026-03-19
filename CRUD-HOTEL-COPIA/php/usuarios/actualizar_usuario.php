<?php
session_start();
require_once '../conexion.php';

// Solo post por admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol_id'] == 1) {
    
    $id_usuario = intval($_POST['id_usuario']);
    $nombre = trim($_POST['nombre_completo']);
    $correo = trim($_POST['correo']);
    $password_raw = $_POST['password']; // Opcional
    $rol = intval($_POST['id_rol']);

    if(empty($nombre) || empty($correo) || $rol === 0 || $id_usuario === 0){
        $_SESSION['error_crud'] = "Faltan datos obligatorios.";
        header("Location: ../../views/usuarios/editar_usuario.php?id=" . $id_usuario);
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    // Validar que el correo no exista en otro usuario
    $stmtCheck = $db->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo AND id_usuario != :id");
    $stmtCheck->bindParam(':correo', $correo);
    $stmtCheck->bindParam(':id', $id_usuario);
    $stmtCheck->execute();
    
    if($stmtCheck->rowCount() > 0){
        $_SESSION['error_crud'] = "El correo ingresado ya está asociado a otra cuenta.";
        header("Location: ../../views/usuarios/editar_usuario.php?id=" . $id_usuario);
        exit();
    }

    // Actualizar con o sin contraseña
    if(!empty($password_raw)){
        $query = "UPDATE usuarios SET nombre_completo = :nombre, correo = :correo, password = :password, id_rol = :rol WHERE id_usuario = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':password', $password_raw);
    } else {
        $query = "UPDATE usuarios SET nombre_completo = :nombre, correo = :correo, id_rol = :rol WHERE id_usuario = :id";
        $stmt = $db->prepare($query);
    }
    
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':rol', $rol);
    $stmt->bindParam(':id', $id_usuario);

    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Usuario actualizado correctamente.";
    } else {
        $_SESSION['error_crud'] = "Error al actualizar el usuario.";
    }

    header("Location: ../../views/usuarios/usuarios.php");
    exit();

} else {
    header("Location: ../../login.php");
    exit();
}
?>
