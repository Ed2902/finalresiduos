<?php

require_once("../conexion.php");

class Ingreso {
    protected $suma_total_kilos;
    protected $fecha_hora;

    public function __construct($suma_total_kilos, $fecha_hora = null) {
        $this->suma_total_kilos = $suma_total_kilos;
        $this->fecha_hora = $fecha_hora ? $fecha_hora : date("Y-m-d H:i:s");
    }

    // Getters y setters
    public function getSumaTotalKilos() {
        return $this->suma_total_kilos;
    }

    public function getFechaHora() {
        return $this->fecha_hora;
    }

    public function setSumaTotalKilos($suma_total_kilos) {
        $this->suma_total_kilos = $suma_total_kilos;
    }

    public function setFechaHora($fecha_hora) {
        $this->fecha_hora = $fecha_hora;
    }

    public function guardar() {
        $conexion = new Conexion();

        try {
            $conexion->beginTransaction();

            // Insertar en la tabla 'ingreso'
            $consultaIngreso = $conexion->prepare("INSERT INTO ingreso (suma_total_kilos, fecha_hora) VALUES (:suma_total_kilos, :fecha_hora)");
            $consultaIngreso->bindParam(':suma_total_kilos', $this->suma_total_kilos);
            $consultaIngreso->bindParam(':fecha_hora', $this->fecha_hora);
            $consultaIngreso->execute();

            // Obtener el ID del último ingreso insertado
            $idIngreso = $conexion->lastInsertId();

            // Confirmar la transacción
            $conexion->commit();

            return $idIngreso; // Devolver el ID del ingreso insertado
        } catch (PDOException $e) {
            // Revertir la transacción si hay un error
            $conexion->rollback();
            error_log("Error al guardar ingreso: " . $e->getMessage());
            return false; // Error
        }
    }

    public static function obtenerTodosLosIngresos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT * FROM ingreso ORDER BY fecha_hora DESC");
        $ingresos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $ingresos;
    }

    public static function obtenerDetallesDeIngreso($idIngreso) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare("SELECT p.nombre, p.referencia, p.tipo, d.cantidad
                                        FROM detalle_ingreso d
                                        INNER JOIN producto p ON d.id_productoFK = p.id_producto
                                        WHERE d.ingreso_id = :idIngreso");
        $consulta->bindParam(':idIngreso', $idIngreso);
        $consulta->execute();
        $detallesIngreso = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $detallesIngreso;
    }
}


?>
