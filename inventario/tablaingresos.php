<?php
include_once "../login/verificar_sesion.php";
// Crear una instancia de la clase Ingreso pendiente en sistema
require_once("./ingreso.php");

$ingreso = new Ingreso($suma_total_kilos, $fecha_hora = null);

// Obtener todos los ingresos
$ingresos = $ingreso->mostrarIngresos();

// Verificar si hay ingresos
if ($ingresos) {
    // Mostrar la tabla de ingresos
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Fecha y Hora</th><th>Total Kilos</th><th>Detalles</th></tr>";
    foreach ($ingresos as $ingreso) {
        echo "<tr>";
        echo "<td>".$ingreso['id']."</td>";
        echo "<td>".$ingreso['fecha_hora']."</td>";
        echo "<td>".$ingreso['suma_total_kilos']."</td>";
        echo "<td>";
        // Obtener y mostrar los detalles del ingreso
        $detalleIngreso = $ingreso->mostrarDetalleIngreso($ingreso['id']);
        echo "<table border='1'>";
        echo "<tr><th>ID Inventario</th><th>Peso</th><th>Proveedor</th><th>Valor por Kilo</th></tr>";
        foreach ($detalleIngreso as $detalle) {
            echo "<tr>";
            echo "<td>".$detalle['id_inventarioFK']."</td>"; // Cambiado de 'id_inventario' a 'id_inventarioFK'
            echo "<td>".$detalle['cantidad']."</td>"; // Cambiado de 'peso' a 'cantidad'
            echo "<td>".$detalle['nombre_proveedor']."</td>";
            echo "<td>".$detalle['valorPorKilo']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    // Mostrar mensaje si no hay ingresos
    echo "No se encontraron ingresos.";
}
?>
