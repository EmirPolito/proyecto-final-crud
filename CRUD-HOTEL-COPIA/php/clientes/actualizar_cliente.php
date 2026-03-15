<?php
session_start();
require_once '../conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = intval($_POST['id_cliente']);
    $nombre = trim($_POST['nombre_completo']);
    $telefono = trim($_POST['telefono']);
    $estado = $_POST['estado'];

    if(empty($nombre) || empty($telefono)){
        $_SESSION['error_crud'] = "Todos los campos son obligatorios.";
        header("Location: ../../views/clientes/editar_cliente.php?id=" . $id_cliente);
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    $query = "UPDATE clientes SET nombre_completo = :nombre, telefono = :telefono, estado = :estado WHERE id_cliente = :id";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":estado", $estado);
    $stmt->bindParam(":id", $id_cliente);

    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Cliente actualizado correctamente.";
        header("Location: ../../views/clientes/clientes.php");
    } else {
        $_SESSION['error_crud'] = "Error al intentar actualizar el cliente.";
        header("Location: ../../views/clientes/editar_cliente.php?id=" . $id_cliente);
    }
} else {
    header("Location: ../../views/clientes/clientes.php");
}
?>
