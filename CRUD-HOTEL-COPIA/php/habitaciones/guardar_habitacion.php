<?php
session_start();
require_once '../conexion.php';

// Control de Acceso: Solo Administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero = trim($_POST['numero']);
    $tipo = trim($_POST['tipo']);
    $precio = floatval($_POST['precio']);
    $estado = $_POST['estado'];

    if(empty($numero) || empty($tipo) || $precio <= 0){
        $_SESSION['error_crud'] = "Datos inválidos o campos vacíos.";
        header("Location: ../../views/nueva_habitacion.php");
        exit();
    }

    $database = new Conexion();
    $db = $database->obtenerConexion();

    // Verificamos si el número de cuarto ya existe
    $stmtCheck = $db->prepare("SELECT numero FROM habitaciones WHERE numero = :num");
    $stmtCheck->bindParam(':num', $numero);
    $stmtCheck->execute();
    if($stmtCheck->rowCount() > 0){
        $_SESSION['error_crud'] = "El número de habitación ya existe.";
        header("Location: ../../views/nueva_habitacion.php");
        exit();
    }

    $query = "INSERT INTO habitaciones (numero, tipo, precio, estado) VALUES (:num, :tipo, :precio, :estado)";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(":num", $numero);
    $stmt->bindParam(":tipo", $tipo);
    $stmt->bindParam(":precio", $precio);
    $stmt->bindParam(":estado", $estado);

    if($stmt->execute()){
        $_SESSION['mensaje_crud'] = "Habitación registrada exitosamente.";
        header("Location: ../../views/habitaciones/habitaciones.php");
    } else {
        $_SESSION['error_crud'] = "Error al registrar la habitación.";
        header("Location: ../../views/nueva_habitacion.php");
    }
} else {
    header("Location: ../../views/habitaciones/habitaciones.php");
}
?>
