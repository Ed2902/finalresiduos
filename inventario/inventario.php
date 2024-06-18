<?php

// Verifica si los archivos existen antes de incluirlos
$conexion_path = realpath(__DIR__ . "/../conexion.php");
$ingreso_path = realpath(__DIR__ . "/../inventario/ingreso.php");



if (file_exists($conexion_path) && file_exists($ingreso_path)) {
    require_once($conexion_path);
    require_once($ingreso_path);
} else {
    die("No se pudieron encontrar los archivos necesarios.");
}

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
                                            SUM(inv.valorPorKilo * inv.peso) AS total_valor
                                      FROM inventario inv
                                      INNER JOIN producto p ON inv.id_productoFK = p.id_producto
                                      GROUP BY p.id_producto");
    
        if ($consulta->rowCount() > 0) {
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td class='text-center'>" . $fila['id_producto'] . "</td>";
                echo "<td class='text-center'>" . $fila['nombre'] . "</td>";
                echo "<td class='text-center'>" . $fila['referencia'] . "</td>";
                echo "<td class='text-center'>" . $fila['tipo'] . "</td>";
                echo "<td class='text-center'>" . number_format($fila['total_cantidad'], 0, ',', '.') . "</td>";
                // Calcular el promedio pagado por kilo
                $promedio_por_kilo = $fila['total_valor'] / $fila['total_cantidad'];
                echo "<td class='text-center'>$" . number_format($promedio_por_kilo, 0, ',', '.') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No se encontraron datos de inventario.</td></tr>";
        }
    
        $conexion = null;
    }
    
    
    public function calcularPromedioPagadoPorKilo() {
        $conexion = new Conexion();
    
        try {
            // Consulta SQL para obtener el total del valor pagado por kilo
            $consulta = $conexion->query("SELECT SUM(inv.valorPorKilo * inv.peso) AS total_valor
                                          FROM inventario inv");
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    
            // Consulta SQL para obtener el total del peso
            $consulta = $conexion->query("SELECT SUM(inv.peso) AS total_cantidad
                                          FROM inventario inv");
            $resultado_cantidad = $consulta->fetch(PDO::FETCH_ASSOC);
    
            // Calcular el promedio pagado por kilo
            if ($resultado && $resultado_cantidad) {
                $total_valor = $resultado['total_valor'];
                $total_cantidad = $resultado_cantidad['total_cantidad'];
    
                if ($total_cantidad > 0) {
                    $promedio_por_kilo = $total_valor / $total_cantidad;
                    return $promedio_por_kilo;
                } else {
                    return 0; // O cualquier valor predeterminado si no hay datos
                }
            } else {
                return 0; // O cualquier valor predeterminado si no hay datos
            }
        } catch (PDOException $e) {
            error_log("Error al calcular promedio pagado por kilo: " . $e->getMessage());
            return 0; // Error
        } finally {
            $conexion = null;
        }
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

    public function obtenerDatosDashboard() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT p.id_producto, p.nombre AS nombre_producto,
                                            SUM(inv.peso) AS peso_total_producto
                                      FROM producto p
                                      LEFT JOIN inventario inv ON p.id_producto = inv.id_productoFK
                                      GROUP BY p.id_producto");
    
        $datos_dashboard = [];
        if ($consulta->rowCount() > 0) {
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $datos_dashboard[] = $fila;
            }
        }
    
        return $datos_dashboard;
    }
    

    public function obtenerNombresProveedores() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT id_proveedor, nombre FROM proveedor");
        $nombres = [];
        if ($consulta->rowCount() > 0) {
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $nombres[$fila['id_proveedor']] = $fila['nombre'];
            }
        }
        return $nombres;
    }
    public function obtenerProductosPorProveedor($idProveedor) {
        $conexion = new Conexion();
        $consulta = $conexion->prepare("SELECT i.id_inventario, p.id_producto, p.nombre AS nombre_producto, u.nombre AS nombre_usuario, i.peso, i.valorPorKilo, ing.fecha_hora 
                                        FROM producto p 
                                        INNER JOIN inventario i ON p.id_producto = i.id_productoFK 
                                        INNER JOIN usuario u ON i.id_usuario = u.id_usuario 
                                        INNER JOIN detalle_ingreso di ON i.id_inventario = di.id_inventarioFK
                                        INNER JOIN ingreso ing ON di.ingreso_id = ing.id
                                        WHERE i.id_proveedor = :id_proveedor");
        $consulta->bindParam(':id_proveedor', $idProveedor);
        $consulta->execute();
        $productos = [];
        if ($consulta->rowCount() > 0) {
            while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
                $productos[] = $fila;
            }
        }
        return $productos;
    }
    

    public function consultarProductosProveedor($idProveedor) {
        $conexion = new Conexion();
        
        try {
            // Consulta SQL para obtener los productos del proveedor
            $consulta = $conexion->prepare("SELECT 
                                                p.id_producto AS id_producto,
                                                p.nombre AS nombre_producto,
                                                p.referencia AS referencia_producto,
                                                i.peso AS cantidad,
                                                (i.peso * i.valorPorKilo) AS valor_total
                                            FROM 
                                                inventario AS i
                                            JOIN 
                                                producto AS p ON i.id_productoFK = p.id_producto
                                            WHERE 
                                                i.id_proveedor = :id_proveedor");
            $consulta->bindParam(':id_proveedor', $idProveedor);
            $consulta->execute();
    
            // Obtener los resultados de la consulta
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
    
            return $resultados;
        } catch (PDOException $e) {
            error_log("Error al consultar productos por proveedor: " . $e->getMessage());
            return false; // Error
        }
    }
    
    public function obtenerTotalKilos() {
        $conexion = new Conexion();
        $consulta = $conexion->query("SELECT SUM(peso) AS total_kilos FROM inventario");
    
        if ($consulta) {
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            $conexion = null;
            return $resultado['total_kilos'];
        } else {
            $conexion = null;
            return 0; // O cualquier valor predeterminado si no hay datos
        }
    }
    
}
?>
