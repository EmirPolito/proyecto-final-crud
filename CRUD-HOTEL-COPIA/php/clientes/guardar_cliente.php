<?php
session_start();
require_once '../conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre_completo']);
    $telefono = trim($_POST['telefono']);
    $estado = $_POST['estado'];

    if(empty($nombre) || empty($telefono)){
        $_SESSION['error_crud'] = "Todos los campos son obligatorios.";
        header("Location: ../../views/nuevo_cliente.php");
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    $query = "INSERT INTO clientes (nombre_completo, telefono, estado) VALUES (:nombre, :telefono, :estado)";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":estado", $estado);

    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Cliente registrado exitosamente.";
        header("Location: ../../views/clientes/clientes.php");
    } else {
        $_SESSION['error_crud'] = "Error al intentar registrar el cliente.";
        header("Location: ../../views/nuevo_cliente.php");
    }
} else {
    header("Location: ../../views/clientes/clientes.php");
}
?>
