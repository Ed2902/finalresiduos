<?php
require_once("../conexion.php");
require_once("../producto/producto.php");

if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];
    $producto = Producto::obtenerProductoPorId($idProducto);

    if ($producto) {
        echo json_encode(array('success' => true, 'producto' => $producto));
    } else {
        echo json_encode(array('success' => false, 'message' => 'No se encontró el producto con el ID proporcionado.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'No se proporcionó un ID de producto.'));
}
?>
