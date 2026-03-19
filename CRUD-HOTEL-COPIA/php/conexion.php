<?php

// Clase llamada Conexion
class Conexion
{
    // Variables privadas, ya que solo se usan dentro de la clase
    private $host = "localhost";
    private $db_name = "crud_hotel_2";
    private $username = "root";
    private $password = "1234";
    public $conn; // Variable donde se almacenará la conexión


    // Método que se encarga de crear y devolver la conexión. PDO para comunicarse con la base de datos
    public function obtenerConexion()
    {
        $this->conn = null; //Se inicializa en null para evitar valores previos
        try {
            // Se crea un objeto de tipo PDO (PHP Data Objects)
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");// Permite manejar correctamente caracteres especiales (acentos, ñ, etc.)


            // Activa el manejo de errores mediante excepciones
            // Si ocurre un error, no pasa desapercibido
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {

            // Captura el error y muestra el mensaje
            echo "Error de conexión: " . $exception->getMessage();
        }

        // Devuelve la conexión para usarla en otras partes del sistema
        return $this->conn;
    }
}
