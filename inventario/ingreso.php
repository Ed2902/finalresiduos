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
    public function mostrarIngresos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT * FROM ingreso");
        $ingresos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $ingresos;
    }

    public function mostrarDetalleIngreso($idIngreso) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare("SELECT d.*, i.id_proveedorFK, i.valorPorKilo, p.nombre AS nombre_proveedor
                                        FROM detalle_ingreso d
                                        INNER JOIN inventario i ON d.id_inventarioFK = i.id_inventario
                                        INNER JOIN proveedor p ON i.id_proveedorFK = p.id_proveedor
                                        WHERE d.ingreso_id = :idIngreso");
        $consulta->bindParam(':idIngreso', $idIngreso);
        $consulta->execute();
        $detalles = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $detalles;
    }
}

?>
