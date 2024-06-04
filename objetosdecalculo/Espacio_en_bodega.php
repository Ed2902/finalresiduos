<?php
require_once '../clases/inventario.php';
;

// Crear una instancia de Inventario
$objInventario = new Inventario(null, null, null, null, null);



// Llamar al método calcularEspacioDisponible()
$resultado = $objInventario->calcularEspacioDisponible();

// Verificar si el resultado es válido
if ($resultado !== false) {
    echo "Espacio disponible: $resultado metros cúbicos";
} else {
    echo "Hubo un error al calcular el espacio disponible.";
}
?>