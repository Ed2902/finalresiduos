<?php
include_once "../login/verificar_sesion.php";
class Conexion extends PDO{
    private $tipo_da_base = "mysql";
    private $host = "host3.latinoamericahosting.com";
    private $nombre_de_base = "fastways_appfastway";
    private $usuario = "fastways_Programador";
    private $contrasena = "Jsqpmlqpors2902";
    private $puerto = "3306";

    public function __construct() {
        try {
            parent::__construct("{$this->tipo_da_base}:dbname={$this->nombre_de_base};host={$this->host};port={$this->puerto};charset=utf8", $this->usuario, $this->contrasena);
        } catch (PDOException $e) {
            echo "Ha surgido un error y no se puede conectar a la base de datos. Detalle: " . $e->getMessage();
            exit;
        }
    }
}
?>
