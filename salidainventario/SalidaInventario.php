<?php

require_once("../conexion.php");

class SalidaInventario {

    private $soportes;
    private $documentos;

    public function __construct($soportes, $documentos) {
        $this->soportes = $soportes;
        $this->documentos = $documentos;
    }

    public function getsoportes() {
        return $this->soportes;
    }

    public function setsoportes($soportes) {
        $this->soportes = $soportes;
    }

    public function getdocumentos() {
        return $this->documentos;
    }

    public function setNombre($documentos) {
        $this->documentos = $documentos;
    }

    public function insertarSalida($evidencias, $documentacion) {
        $conexion = new Conexion();

        try {
            $conexion->beginTransaction();

            // Obtener datos de la tabla 'inventario'
            $consulta = $conexion->query("SELECT * FROM inventario");
            $inventario = $consulta->fetchAll(PDO::FETCH_ASSOC);

            if (empty($inventario)) {
                throw new Exception("No hay productos en inventario para insertar en salida.");
            }

            // Calcular la suma total de kilos y el valor total
            $sumaTotalKilos = 0;
            $valorTotal = 0;
            foreach ($inventario as $producto) {
                // Multiplicar el peso por el valorPorKilo y sumar al valor total
                $valorTotal += $producto['peso'] * $producto['valorPorKilo'];
                // Sumar los pesos
                $sumaTotalKilos += $producto['peso'];
            }

            // Insertar en la tabla 'salida'
            $fechaHora = date('Y-m-d H:i:s');
            $consultaSalida = $conexion->prepare("INSERT INTO salida (suma_total_kilos, fecha_hora, valor_total, evidencias, documentacion) VALUES (:suma_total_kilos, :fecha_hora, :valor_total, :evidencias, :documentacion)");
            $consultaSalida->bindParam(':suma_total_kilos', $sumaTotalKilos);
            $consultaSalida->bindParam(':fecha_hora', $fechaHora);
            $consultaSalida->bindParam(':valor_total', $valorTotal);
            $consultaSalida->bindParam(':evidencias', $evidencias);
            $consultaSalida->bindParam(':documentacion', $documentacion);
            $consultaSalida->execute();

            // Confirmar la transacción
            $conexion->commit();

            return true; // Éxito
        } catch (PDOException $e) {
            // Revertir la transacción si hay un error
            $conexion->rollback();
            error_log("Error al insertar salida: " . $e->getMessage());
            return false; // Error
        } catch (Exception $e) {
            // Capturar otros tipos de excepciones
            $conexion->rollback();
            error_log("Error al insertar salida: " . $e->getMessage());
            return false; // Error
        }
    }

    public function moverInventarioToOld() {
        $conexion = new Conexion();
        
        try {
            $conexion->beginTransaction();

            // Obtener todos los registros de la tabla 'inventario'
            $consulta = $conexion->query("SELECT * FROM inventario");
            $inventario = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($inventario)) {
                throw new Exception("No hay productos en el inventario para mover.");
            }

            $id_salida = $this->obtenerUltimoIdSalida($conexion); // Obtener el último ID de salida y almacenarlo en una variable

            // Insertar cada registro en la tabla 'inventario_old' y guardar el ID de salida
            foreach ($inventario as $producto) {
                $consultaInventarioOld = $conexion->prepare("INSERT INTO inventario_old (id_productoFK, id_usuario, peso, id_proveedor, valorPorKilo, id_salida) VALUES (:id_productoFK, :id_usuario, :peso, :id_proveedor, :valorPorKilo, :id_salida)");
                $consultaInventarioOld->bindParam(':id_productoFK', $producto['id_productoFK']);
                $consultaInventarioOld->bindParam(':id_usuario', $producto['id_usuario']);
                $consultaInventarioOld->bindParam(':peso', $producto['peso']);
                $consultaInventarioOld->bindParam(':id_proveedor', $producto['id_proveedor']);
                $consultaInventarioOld->bindParam(':valorPorKilo', $producto['valorPorKilo']);
                $consultaInventarioOld->bindParam(':id_salida', $id_salida); // Pasar la variable en lugar de la función
                $consultaInventarioOld->execute();
            }

            // Eliminar todos los registros de la tabla 'inventario'
            $conexion->exec("DELETE FROM inventario");

            // Confirmar la transacción
            $conexion->commit();

            return true; // Éxito
        } catch (PDOException $e) {
            // Revertir la transacción si hay un error
            $conexion->rollback();
            error_log("Error al mover inventario a inventario_old: " . $e->getMessage());
            return false; // Error
        } catch (Exception $e) {
            // Capturar otros tipos de excepciones
            $conexion->rollback();
            error_log("Error al mover inventario a inventario_old: " . $e->getMessage());
            return false; // Error
        }
    }

    private function obtenerUltimoIdSalida($conexion) {
        // Obtener el último ID insertado en la tabla 'salida'
        $consulta = $conexion->query("SELECT MAX(id) AS max_id FROM salida");
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado['max_id'];
    }

    public function consultarSalidas() {
        $conexion = new Conexion();
        try {
            $consulta = $conexion->query("SELECT * FROM salida");
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al consultar salidas: " . $e->getMessage());
            return [];
        }
    }
}
?>
