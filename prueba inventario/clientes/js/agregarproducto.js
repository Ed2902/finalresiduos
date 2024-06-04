function validarFormulario() {
    var codigoProducto = document.getElementById("CodigoProducto").value;
    var referencia = document.getElementById("Referencia").value;
    var tipo = document.getElementById("tipo").value;
    var marca = document.getElementById("Marca").value;
    var ancho = parseFloat(document.getElementById("ancho").value);
    var alto = parseFloat(document.getElementById("Alto").value);
    var profundo = parseFloat(document.getElementById("Profundo").value);
    var usuario = document.getElementById("Usuario").value;
    var fw = document.getElementById("FW").value;
    var cantidadesAgregar = parseInt(document.getElementById("CantidadesAgregar").value);

    // Validación de campos obligatorios
    if (codigoProducto === "" || referencia === "" || tipo === "" || marca === "" || usuario === "" || fw === "") {
        alert("Todos los campos son obligatorios");
        return false;
    }

    // Validación de cantidad como número entero
    if (isNaN(cantidadesAgregar) || cantidadesAgregar % 1 !== 0) {
        alert("La cantidad debe ser un número entero");
        return false;
    }

    // Validación de ancho, alto y profundo como valores float
    if (isNaN(ancho) || isNaN(alto) || isNaN(profundo)) {
        alert("Ancho, alto y profundo deben ser valores numéricos");
        return false;
    }

    // Resto del código para enviar el formulario si todas las validaciones son exitosas
    // document.getElementById("Producto").submit();
    limpiarFormulario();

    alert("Producto agregado");

    return true;
}

function limpiarFormulario() {
document.getElementById("CodigoProducto").value = "";
document.getElementById("Referencia").value = "";
document.getElementById("tipo").value = "";
document.getElementById("Marca").value = "";
document.getElementById("ancho").value = "";
document.getElementById("Alto").value = "";
document.getElementById("Profundo").value = "";
document.getElementById("Usuario").value = "";
document.getElementById("FW").value = "";
document.getElementById("CantidadesAgregar").value = "";
}