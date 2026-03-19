<?php
session_start();
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if(empty($correo) || empty($password)){
        $_SESSION['error_login'] = "Por favor, completa todos los campos.";
        header("Location: ../../login.php");
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    // Sin chequeos de intentos o bloqueos para la copia simple
    $query = "SELECT u.id_usuario, u.nombre_completo, u.password, u.id_rol, r.nombre_rol 
              FROM usuarios u 
              JOIN roles r ON u.id_rol = r.id_rol 
              WHERE u.correo = :correo LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($password === $usuario['password']){
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
            $_SESSION['usuario_rol_id'] = $usuario['id_rol'];
            $_SESSION['usuario_rol_nombre'] = $usuario['nombre_rol'];

            header("Location: ../../views/panel.php");
            exit();

        } else {
            $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
            header("Location: ../../login.php");
            exit();
        }

    } else {
        $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
        header("Location: ../../login.php");
        exit();
    }

} else {
    header("Location: ../../login.php");
    exit();
}
?>
