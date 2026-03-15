<?php
class Conexion {
    private $host = "localhost";
    private $db_name = "crud_hotel_2";
    private $username = "root"; // Ajustar si es necesario
    private $password = "1234";     // En XAMPP por defecto la contraseña de root va vacía
    public $conn;

    public function obtenerConexion() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
