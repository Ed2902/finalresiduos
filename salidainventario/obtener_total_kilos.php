<?php
include_once "../login/verificar_sesion.php";
require_once('../inventario/inventario.php');

$inventario = new Inventario(null, null, null, null, null, null);
$total_kilos = $inventario->obtenerTotalKilos();

echo $total_kilos;
?>
