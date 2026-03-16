<?php
session_start();
require_once '../conexion.php';

// Solo post por admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol_id'] == 1) {
    
    $nombre = trim($_POST['nombre_completo']);
    $correo = trim($_POST['correo']);
    $password_raw = $_POST['password'];
    $rol = intval($_POST['id_rol']);

    if(empty($nombre) || empty($correo) || empty($password_raw) || $rol === 0){
        $_SESSION['error_crud'] = "Todos los campos son obligatorios.";
        header("Location: ../../views/usuarios/usuarios.php");
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    // Validar que el correo no exista
    $stmtCheck = $db->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo");
    $stmtCheck->bindParam(':correo', $correo);
    $stmtCheck->execute();
    
    if($stmtCheck->rowCount() > 0){
        $_SESSION['error_crud'] = "El correo ingresado ya está registrado.";
        header("Location: ../../views/usuarios/usuarios.php");
        exit();
    }

    // Guardar contraseña en texto plano (como solicitado para la versión demostrativa)
    $query = "INSERT INTO usuarios (nombre_completo, correo, password, id_rol) 
              VALUES (:nombre, :correo, :password, :rol)";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':password', $password_raw);
    $stmt->bindParam(':rol', $rol);

    if($stmt->execute()){
        $id_nuevo_usuario = $db->lastInsertId();
        
        // Si es cliente (rol 2), crear su registro en clientes
        if($rol == 2){
            $stmtCliente = $db->prepare("INSERT INTO clientes (id_usuario, nombre_completo, telefono, estado) VALUES (:id_usuario, :nombre, '000-0000', 'Activo')");
            $stmtCliente->bindParam(':id_usuario', $id_nuevo_usuario);
            $stmtCliente->bindParam(':nombre', $nombre);
            $stmtCliente->execute();
        }

        $_SESSION['mensaje_crud'] = "Usuario registrado correctamente.";
    } else {
        $_SESSION['error_crud'] = "Ocurrió un error al registrar el usuario.";
    }

    header("Location: ../../views/usuarios/usuarios.php");
    exit();

} else {
    header("Location: ../../index.php");
    exit();
}
?>
