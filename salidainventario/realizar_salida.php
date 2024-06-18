<?php

require_once("./SalidaInventario.php"); // Ajusta la ruta a la clase SalidaInventario
require_once("../conexion.php"); // Ajusta la ruta a la clase Conexion

// Rutas donde se guardarán los archivos subidos
$uploadDirEvidencias = 'uploads/evidencias/'; // Carpeta para guardar las evidencias
$uploadDirDocumentacion = 'uploads/documentacion/'; // Carpeta para guardar la documentación

// Crear las carpetas si no existen
if (!is_dir($uploadDirEvidencias)) {
    mkdir($uploadDirEvidencias, 0777, true);
}

if (!is_dir($uploadDirDocumentacion)) {
    mkdir($uploadDirDocumentacion, 0777, true);
}

// Función para mover archivos
function moveFile($file, $targetDir) {
    // Verificar si se está moviendo un solo archivo o varios
    if (is_array($file['name'])) {
        // Mover varios archivos
        $paths = [];
        foreach ($file['name'] as $key => $fileName) {
            $targetFilePath = $targetDir . basename($fileName);
            if (move_uploaded_file($file['tmp_name'][$key], $targetFilePath)) {
                $paths[] = $targetFilePath;
            } else {
                echo "Error al subir el archivo $fileName a $targetDir.";
                return false;
            }
        }
        return implode(",", $paths);
    } else {
        // Mover un solo archivo
        $fileName = basename($file['name']);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            return $targetFilePath;
        } else {
            echo "Error al subir el archivo $fileName a $targetDir.";
            return false;
        }
    }
}

// Verificar si se han subido archivos para 'evidencias' y 'documentacion'
$evidenciasPath = "";
$documentacionPath = "";
if (!empty($_FILES['evidencias']['name'][0])) {
    $evidenciasPath = moveFile($_FILES['evidencias'], $uploadDirEvidencias);
}
if (!empty($_FILES['documentacion']['name'][0])) {
    $documentacionPath = moveFile($_FILES['documentacion'], $uploadDirDocumentacion);
}

// Instanciar la clase SalidaInventario
$salidaInventario = new SalidaInventario($evidenciasPath, $documentacionPath);

// Insertar la salida
if ($salidaInventario->insertarSalida($evidenciasPath, $documentacionPath)) {
    // Mover inventario a inventario_old
    if ($salidaInventario->moverInventarioToOld()) {
        // Mostrar alert de éxito y redirigir al home
        echo "<script>
                alert('Operación exitosa: inventario en cero.');
                window.location.href = '../index.html'; // Cambia 'home.php' por la ruta a tu página principal
              </script>";
    } else {
        echo "<script>
                alert('Error al mover el inventario a inventario_old. Verifica los logs de error para más detalles.');
              </script>";
    }
} else {
    echo "<script>
            alert('Error al insertar la salida. Verifica los logs de error para más detalles.');
          </script>";
}
?>