// Función para agregar una nueva fila a la tabla
function agregarFila() {
    // Capturar los valores del formulario
    var referencia = document.getElementById("Referencia").value;
    var codigo = document.getElementById("Codigo").value;
    var descripcion = document.getElementById("Descripcion").value;
    var lote = document.getElementById("Lote").value;
    var total = document.getElementById("Total").value;

    // Validar campos obligatorios
    if (!referencia || !descripcion || !lote || !total) {
        alert("Todos los campos son obligatorios, excepto Codigo");
        return;
    }

    // Validar que el campo "Total" sea un número entero
    if (!Number.isInteger(parseFloat(total)) || parseFloat(total) % 1 !== 0) {
        alert("El campo 'Total' debe ser un número entero.");
        return;
    }
    

    // Crear una nueva fila en la tabla
    var tabla = document.getElementById("tablaBody");
    var fila = tabla.insertRow();
    var celda1 = fila.insertCell(0);
    var celda2 = fila.insertCell(1);
    var celda3 = fila.insertCell(2);
    var celda4 = fila.insertCell(3);
    var celda5 = fila.insertCell(4);
    var celda6 = fila.insertCell(5);

    // Llenar las celdas con los valores capturados
    celda1.innerHTML = referencia;
    celda2.innerHTML = codigo;
    celda3.innerHTML = descripcion;
    celda4.innerHTML = lote;
    celda5.innerHTML = total;
    celda6.innerHTML = '<button class="btn btn-danger" onclick="eliminarFila(this)">Eliminar</button> <button class="btn btn-warning" onclick="modificarFila(this)">Modificar</button>';

    // Limpiar los campos del formulario
    limpiarFormulario();
}

// Función para limpiar el formulario
function limpiarFormulario() {
    document.getElementById("myforms").reset();
}

// Función para eliminar una fila de la tabla
function eliminarFila(boton) {
    var fila = boton.parentNode.parentNode;
    fila.parentNode.removeChild(fila);
}

// Función para modificar una fila de la tabla
function modificarFila(boton) {
    var fila = boton.parentNode.parentNode;
    var referencia = fila.cells[0].innerHTML;
    var codigo = fila.cells[1].innerHTML;
    var descripcion = fila.cells[2].innerHTML;
    var lote = fila.cells[3].innerHTML;
    var total = fila.cells[4].innerHTML;

    // Rellenar el formulario con los datos de la fila seleccionada
    document.getElementById("Referencia").value = referencia;
    document.getElementById("Codigo").value = codigo;
    document.getElementById("Descripcion").value = descripcion;
    document.getElementById("Lote").value = lote;
    document.getElementById("Total").value = total;

    // Eliminar la fila actual
    fila.parentNode.removeChild(fila);
}

///////////////////////////////////////////////// agregar al inventario //////////////////////

function abrirModal() {
    document.getElementById("myModal").style.display = "block";
}

// Función para enviar datos adicionales desde la ventana modal
function enviarDatosAdicionales() {
    // Lógica para enviar los datos adicionales del formulario modal
    console.log("Datos adicionales enviados");

    // Puedes acceder a los datos de la ventana modal utilizando document.getElementById y los IDs de los campos
    var empresaCliente = document.getElementById("EmpresaCliente").value;
    var ccReclama = document.getElementById("CCReclama").value;
    var placa = document.getElementById("Placa").value;
    var telefonoRecoge = document.getElementById("TelefonoRecoge").value;
    var horaRecogida = document.getElementById("HoraRecogida").value;

    console.log("Empresa o Cliente: " + empresaCliente);
    console.log("C.C Quien Reclama: " + ccReclama);
    console.log("Placa (Quien Recoge): " + placa);
    console.log("Teléfono (Quien Recoge): " + telefonoRecoge);
    console.log("Hora de Recogida: " + horaRecogida);

    // Puedes realizar más acciones aquí, como enviar los datos a un servidor, etc.
    
    // Cerrar la ventana modal después de enviar datos
    cerrarModal();
}

// Función para editar una fila
function editarFila(button) {
    var row = button.parentNode.parentNode;

    // Obtener los valores de la fila
    var codigoProducto = row.cells[0].innerHTML;
    var referencia = row.cells[1].innerHTML;
    var diseno = row.cells[2].innerHTML;
    var marca = row.cells[3].innerHTML;
    var quienDaIngreso = row.cells[4].innerHTML;
    var cantidadesAgregar = row.cells[5].innerHTML;

    // Llenar el formulario con los valores de la fila
    document.getElementById("CodigoProducto").value = codigoProducto;
    document.getElementById("Referencia").value = referencia;
    document.getElementById("Diseno").value = diseno;
    document.getElementById("Marca").value = marca;
    document.getElementById("QuienDaIngreso").value = quienDaIngreso;
    document.getElementById("CantidadesAgregar").value = cantidadesAgregar;

    // Eliminar la fila después de cargar los valores en el formulario
    row.parentNode.removeChild(row);
}