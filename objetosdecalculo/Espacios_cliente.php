<?php

require_once '../clases/inventario.php';

// Crear una instancia de Inventario
$objInventario = new Inventario(null, null, null, null, null);

// Luego puedes llamar a los métodos de tu objeto $objInventario, como calcularEspacioCliente()
$resultado_por_cliente = $objInventario->calcularEspacioCliente();


// Verificar si el resultado es válido
if ($resultado_por_cliente !== false) {
    foreach ($resultado_por_cliente as $id_cliente => $espacio_ocupado) {
        // Limitar a 2 decimales
        $espacio_ocupado = number_format($espacio_ocupado, 3);
        echo "Cliente ID $id_cliente - Espacio ocupado: $espacio_ocupado metros cúbicos<br>";
    }
} else {
    echo "Hubo un error al calcular el espacio por cliente.";
}

?>
