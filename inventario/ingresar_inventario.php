<?php include_once "../login/verificar_sesion.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-1 d-none d-sm-block">
                <a href="javascript:history.back()" class="btn-link fasbtn btn-link mt-2 ml-2"><i class="fas fa-arrow-left" style="color: red;"></i></a>
            </div>
            <div class="col-11">
                <div class="row justify-content-center">
                    <div class="col-md-9">
                    <form id="formularioInventario" class="text-center border border-light p-3 shadow-lg rounded-lg" style="border-radius: 18px; margin-top: 18px;">
                        <p class="h2 mb-4">Ingreso de Inventario</p>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_productoFK" class="form-label">Código Producto</label>
                                <input type="text" class="form-control" id="id_productoFK" name="id_productoFK" placeholder="Código Producto">
                            </div>
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="referencia" class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="referencia" name="referencia" placeholder="Referencia">
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Tipo">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_usuarioFK" class="form-label">Quién da el ingreso</label>
                                <input type="number" class="form-control" id="id_usuarioFK" name="id_usuarioFK" placeholder="Quién da el ingreso">
                            </div>
                            <div class="col-md-6">
                                <label for="peso" class="form-label">Peso a Agregar (kg)</label>
                                <input type="number" class="form-control" id="peso" name="peso" placeholder="Peso a Agregar (kg)">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_proveedorFK" class="form-label">Proveedor</label>
                                <input type="text" class="form-control" id="id_proveedorFK" name="id_proveedorFK" placeholder="Proveedor">
                            </div>
                            <div class="col-md-6">
                                <label for="valorPorKilo" class="form-label">Valor por Kilo</label>
                                <input type="text" class="form-control" id="valorPorKilo" name="valorPorKilo" placeholder="Valor por Kilo">
                            </div>
                        </div>
                        <button type="button" id="agregar" class="boton_agregar btn btn-info btn-lg">Agregar</button>
                    </form>
                        <input type="hidden" id="RowIndex" name="RowIndex" value="">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-9 mx-auto">
                        <div class="table-responsive">
                            <table class="table" id="lista">
                                <thead>
                                    <tr>
                                        <th>Código Producto</th>
                                        <th>Nombre</th>
                                        <th>Referencia</th>
                                        <th>Tipo</th>
                                        <th>Quién da el ingreso</th>
                                        <th>Peso</th>
                                        <th>Cliente</th>
                                        <th>Valor Kilo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="totalsuma col-10 text-right" id="total"></div>
                <div class="row mt-4">
                    <div class="col-md-9 mx-auto text-center">
                        <button type="button" id="guardar" class="boton_enviar btn btn-success btn-lg">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('id_productoFK').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                var idProducto = document.getElementById('id_productoFK').value;
                fetch('./obtener_producto.php?id=' + idProducto)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('nombre').value = data.producto.nombre;
                            document.getElementById('referencia').value = data.producto.referencia;
                            document.getElementById('tipo').value = data.producto.tipo;
                        } else {
                            alert('No se encontró el producto con el ID proporcionado.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="./teste.js"></script>
</body>
</html>
