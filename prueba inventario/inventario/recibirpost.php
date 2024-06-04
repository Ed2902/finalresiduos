<?php

// Incluir las clases Inventario e Ingreso
require_once("./inventario.php");
require_once("./ingreso.php");

// Obtener los datos del inventario del cuerpo de la solicitud
$inventarioData = json_decode(file_get_contents("php://input"), true);

// Verificar si los datos son válidos
if (!$inventarioData || !is_array($inventarioData)) {
    echo json_encode(array("error" => "Los datos de inventario no son válidos."));
    exit; // Detener la ejecución del script
}

// Inicializar un array para almacenar mensajes de respuesta
$responses = [];

// Calcular la suma total de kilos
$sumaTotalKilos = 0;
foreach ($inventarioData as $filaDatos) {
    $sumaTotalKilos += $filaDatos['peso'];
}

// Crear un objeto Ingreso y guardarlo en la base de datos
$ingreso = new Ingreso($sumaTotalKilos);
$idIngreso = $ingreso->guardar();

if ($idIngreso === false) {
    echo json_encode(array("error" => "Error al guardar el ingreso en la base de datos."));
    exit; // Detener la ejecución del script
}

// Recorrer los datos del inventario y guardarlos en la base de datos
foreach ($inventarioData as $filaDatos) {
    // Crear un objeto Inventario con los datos de la fila
    $inventario = new Inventario(
        $filaDatos['id_productoFK'],
        $filaDatos['nombre'],
        $filaDatos['referencia'],
        $filaDatos['tipo'],
        $filaDatos['id_usuarioFK'], // Ajustar para incluir el ID de usuario
        $filaDatos['peso'],
        $filaDatos['id_proveedorFK'],
        $filaDatos['valorPorKilo']
    );

    // Intentar guardar el inventario en la base de datos
    $inventarioGuardado = $inventario->guardar($idIngreso);

    // Verificar si el inventario se guardó correctamente
    if ($inventarioGuardado) {
        $responses[] = array("mensaje" => "Datos de inventario guardados con éxito para el producto con ID " . $filaDatos['id_productoFK']);
    } else {
        $responses[] = array("error" => "Error al guardar el inventario en la base de datos para el producto con ID " . $filaDatos['id_productoFK']);
    }
}

// Devolver todas las respuestas como JSON
echo json_encode($responses);

?>
