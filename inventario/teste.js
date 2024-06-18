var boton = document.getElementById('agregar');
var guardar = document.getElementById('guardar');
var lista = document.getElementById('lista');
var data = [];  
var cant = 0;

// Eventos para botones
boton.addEventListener("click", agregar);
guardar.addEventListener("click", save);

// Deshabilitar edición de campos nombre, referencia y tipo
document.getElementById('nombre').disabled = true;
document.getElementById('referencia').disabled = true;
document.getElementById('tipo').disabled = true;

// Función para agregar productos al array y a la tabla visual
function agregar() {
    var id_productoFK = document.getElementById('id_productoFK').value;
    var nombre = document.getElementById('nombre').value;
    var referencia = document.getElementById('referencia').value;
    var tipo = document.getElementById('tipo').value;
    var id_usuarioFK = document.getElementById('id_usuarioFK').value;
    var peso = document.getElementById('peso').value;
    var id_proveedorFK = document.getElementById('id_proveedorFK').value;
    var valorPorKilo = document.getElementById('valorPorKilo').value;

    // Validar campos obligatorios
    if (id_productoFK === '' || id_usuarioFK === '' || peso === '' || id_proveedorFK === '' || valorPorKilo === '') {
        alert('Todos los campos son obligatorios.');
        return;
    }

    // Agregar elementos al array data
    data.push({
        "cant": cant,
        "id_productoFK": id_productoFK,
        "nombre": nombre,
        "referencia": referencia,
        "tipo": tipo,
        "id_usuarioFK": id_usuarioFK,
        "peso": peso,
        "id_proveedorFK": id_proveedorFK,
        "valorPorKilo": valorPorKilo,
    });

    // Crear fila de la tabla con los datos del producto
    var fila = '<tr id="row' + cant + '"><td>' + id_productoFK + '</td><td>' + nombre + '</td><td>' + referencia + '</td><td>' + tipo + '</td><td>' + id_usuarioFK + '</td><td>' + peso + '</td><td>' + id_proveedorFK + '</td><td>' + valorPorKilo + '</td><td><a href="#" class="btn btn-danger bg-danger" onclick="eliminar(' + cant + ');">Eliminar</a></td><td><a href="#" class="btn btn-warning bg-warning" onclick="cantidad(' + cant + ');">Editar</a></td></tr>';

    // Agregar fila a la tabla
    $("#lista").append(fila);

    // Limpiar formulario
    $("#id_productoFK").val('');
    $("#nombre").val('');
    $("#referencia").val('');
    $("#tipo").val('');
    $("#id_usuarioFK").val('');
    $("#peso").val('');
    $("#id_proveedorFK").val('');
    $("#valorPorKilo").val('');
    $("#id_productoFK").focus();

    // Incrementar contador
    cant++;
}

// Función para eliminar fila de la tabla y el elemento correspondiente del array
function eliminar(index) {
    // Eliminar fila de la tabla
    $("#row" + index).remove();

    // Filtrar array para eliminar el elemento con el índice dado
    data = data.filter(function(item) {
        return item.cant !== index;
    });
}

// Función para editar los datos de un producto
function cantidad(index) {
    // Encontrar elemento en el array
    var item = data.find(function(item) {
        return item.cant === index;
    });

    // Si el elemento existe, llenar formulario con sus datos
    if (item) {
        $("#id_productoFK").val(item.id_productoFK);
        $("#nombre").val(item.nombre);
        $("#referencia").val(item.referencia);
        $("#tipo").val(item.tipo);

        // Eliminar fila visual y del array
        $("#row" + index).remove();
        data = data.filter(function(item) {
            return item.cant !== index;
        });
    }
}

function prepararDatosParaEnvio(data) {
    return data.map(function(producto) {
        // Crear un nuevo objeto sin los campos 'nombre', 'referencia' y 'tipo'
        var productoModificado = {
            "cant": producto.cant,
            "id_productoFK": producto.id_productoFK,
            "id_usuarioFK": producto.id_usuarioFK,
            "peso": producto.peso,
            "id_proveedorFK": producto.id_proveedorFK,
            "valorPorKilo": producto.valorPorKilo,
        };
        return productoModificado;
    });
}

// Función para enviar los datos al servidor
function save() {
    var dataToSend = prepararDatosParaEnvio(data);
    var json = JSON.stringify(data);  // Convertir array de datos a formato JSON
    console.log("JSON a enviar:", json); 

    // Crear una solicitud AJAX
    var xhr = new XMLHttpRequest();
    var url = './recibirpost.php';
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Configurar la función de devolución de llamada cuando la solicitud se complete
    xhr.onload = function () {
        if (xhr.status === 200) {
            // La solicitud fue exitosa
            console.log('Respuesta del servidor:', xhr.responseText);
            console.log('Datos enviados:', json);
            alert('Los datos han sido guardados exitosamente.');
            // Redireccionar al home
            window.location.href = '../Home/home.php';
        } else {
            // Hubo un error en la solicitud
            console.error('Error al enviar datos al servidor. Código de estado:', xhr.status);
        }
    };

    // Configurar la función de devolución de llamada para errores de red
    xhr.onerror = function () {
        // Hubo un error de red
        console.error('Error de red al enviar datos al servidor.');
    };

    // Enviar la solicitud con los datos JSON
    xhr.send(json);
}
