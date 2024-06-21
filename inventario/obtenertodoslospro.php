<?php
require_once('../producto/producto.php');

header('Content-Type: application/json');

try {
    $productos = producto::obtenerTodosLosProductos();
    echo json_encode(['success' => true, 'productos' => $productos]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
