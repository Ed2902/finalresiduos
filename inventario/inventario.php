<?php

require_once("../conexion.php");
require_once("./ingreso.php");

class Inventario {
    protected $id_productoFK;
    protected $id_usuarioFK;
    protected $peso;
    protected $id_proveedorFK;
    protected $valorPorKilo;

    public function __construct($id_productoFK, $id_usuarioFK, $peso, $id_proveedorFK, $valorPorKilo) {
        $this->id_productoFK = $id_productoFK;
        $this->id_usuarioFK = $id_usuarioFK;
        $this->peso = $peso;
        $this->id_proveedorFK = $id_proveedorFK;
        $this->valorPorKilo = $valorPorKilo;
    }

    // Getters y setters
    public function getIdProductoFK() {
        return $this->id_productoFK;
    }

    public function getIdUsuarioFK() {
        return $this->id_usuarioFK;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function getIdProveedorFK() {
        return $this->id_proveedorFK;
    }

    public function getValorPorKilo() {
        return $this->valorPorKilo;
    }

    public function setIdProductoFK($id_productoFK) {
        $this->id_productoFK = $id_productoFK;
    }

    public function setIdUsuarioFK($id_usuarioFK) {
        $this->id_usuarioFK = $id_usuarioFK;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }

    public function setIdProveedorFK($id_proveedorFK) {
        $this->id_proveedorFK = $id_proveedorFK;
    }

    public function setValorPorKilo($valorPorKilo) {
        $this->valorPorKilo = $valorPorKilo;
    }

    public function guardar($idIngreso) {
        $conexion = new Conexion();
        
        try {
            $conexion->beginTransaction();

            // Insertar en la tabla 'inventario'
            $consultaInventario = $conexion->prepare("INSERT INTO inventario (id_productoFK, id_usuario, peso, id_proveedor, valorPorKilo) VALUES (:id_productoFK, :id_usuario, :peso, :id_proveedor, :valorPorKilo)");
            $consultaInventario->bindParam(':id_productoFK', $this->id_productoFK);
            $consultaInventario->bindParam(':id_usuario', $this->id_usuarioFK);
            $consultaInventario->bindParam(':peso', $this->peso);
            $consultaInventario->bindParam(':id_proveedor', $this->id_proveedorFK);
            $consultaInventario->bindParam(':valorPorKilo', $this->valorPorKilo);
            $consultaInventario->execute();

            // Obtener el ID del último producto insertado en inventario
            $idProducto = $conexion->lastInsertId();
            
            // Insertar en la tabla 'detalle_ingreso'
            $consultaDetalleIngreso = $conexion->prepare("INSERT INTO detalle_ingreso (ingreso_id, id_inventarioFK, cantidad) VALUES (:ingreso_id, :id_inventarioFK, :cantidad)");
            $consultaDetalleIngreso->bindParam(':ingreso_id', $idIngreso);
            $consultaDetalleIngreso->bindParam(':id_inventarioFK', $idProducto);
            $consultaDetalleIngreso->bindParam(':cantidad', $this->peso);
            $consultaDetalleIngreso->execute();

            // Confirmar la transacción
            $conexion->commit();

            return true; // Éxito
        } catch (PDOException $e) {
            // Revertir la transacción si hay un error
            $conexion->rollback();
            error_log("Error al guardar inventario: " . $e->getMessage());
            return false; // Error
        }
    }

    public function mostrarConsolidadoProductos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT p.id_producto, p.nombre AS nombre, p.referencia, p.tipo, 
                                            SUM(inv.peso) AS total_cantidad,
                                            SUM(inv.valorPorKilo) AS total_valor
                                      FROM inventario inv
                                      INNER JOIN producto p ON inv.id_productoFK = p.id_producto
                                      GROUP BY p.id_producto");
    
        if ($consulta->rowCount() > 0) {
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$fila['id_producto']."</td>";
                echo "<td>".$fila['nombre']."</td>";
                echo "<td>".$fila['referencia']."</td>";
                echo "<td>".$fila['tipo']."</td>";
                echo "<td>" . number_format($fila['total_cantidad'], 2, ',', '.') . "</td>";
                echo "<td>$" . number_format($fila['total_valor'], 0, ',', '.') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron datos de inventario.</td></tr>";
        }
    
        $conexion = null;
    }
    
    public function calcularTotalesProductos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT SUM(inv.peso) AS total_cantidad,
                                            SUM(inv.valorPorKilo) AS total_valor
                                      FROM inventario inv
                                      INNER JOIN producto p ON inv.id_productoFK = p.id_producto");
        
        $totales = $consulta->fetch(PDO::FETCH_ASSOC);
        $conexion = null;
    
        // Si se encontraron resultados, devolver los totales calculados
        if ($totales) {
            return $totales;
        } else {
            return ['total_cantidad' => 0, 'total_valor' => 0];
        }
    }
    
}
?>
