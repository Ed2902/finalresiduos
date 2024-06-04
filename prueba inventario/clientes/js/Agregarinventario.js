var inventarioData = [];  // Array para almacenar los datos del inventario

function agregarFilaTabla() {
    var codigoProducto = document.getElementById("CodigoProducto").value;
    var referencia = document.getElementById("Referencia").value;
    var tipo = document.getElementById("tipo").value;
    var marca = document.getElementById("Marca").value;
    var quienDaIngreso = document.getElementById("QuienDaIngreso").value;
    var Fw = document.getElementById("FW").value;
    var cantidadesAgregar = document.getElementById("CantidadesAgregar").value;

    if (!referencia || !tipo || !marca || !quienDaIngreso || !Fw || !cantidadesAgregar) {
        alert("Todos los campos son obligatorios. Por favor, complete todos los campos.");
        return;
    }

    // Validar que Quién da el ingreso sea un valor de texto
    if (!/^[A-Za-z\s]+$/.test(quienDaIngreso)) {
        alert("Por favor, ingrese un valor de texto en el campo 'Quién da el ingreso'.");
        return;
    }

    if (!/^\d+$/.test(cantidadesAgregar)) {
        alert("Por favor, ingrese un número entero en el campo 'Cantidades a Agregar'.");
        return;
    }

    var table = document.getElementById("tablaInventario");
    var newRow = table.insertRow(table.rows.length);

    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    var cell6 = newRow.insertCell(5);
    var cell7 = newRow.insertCell(6);
    var cell8 = newRow.insertCell(7);

    cell1.innerHTML = codigoProducto;
    cell2.innerHTML = referencia;
    cell3.innerHTML = tipo;
    cell4.innerHTML = marca;
    cell5.innerHTML = quienDaIngreso;
    cell6.innerHTML = Fw;
    cell7.innerHTML = cantidadesAgregar;

    // Crear botones de acciones
    var editButton = document.createElement("button");
    editButton.setAttribute("class", "btn btn-warning btn-sm");
    editButton.setAttribute("onclick", "editarFila(this)");
    editButton.innerHTML = "Editar";

    var deleteButton = document.createElement("button");
    deleteButton.setAttribute("class", "btn btn-danger btn-sm");
    deleteButton.setAttribute("onclick", "eliminarFila(this)");
    deleteButton.innerHTML = "Eliminar";

    // Agregar botones a la celda de acciones
    cell8.appendChild(editButton);
    cell8.appendChild(deleteButton);

    // Almacenar datos en el array
    var filaDatos = {
        CodigoProducto: codigoProducto,
        Referencia: referencia,
        Tipo: tipo,  
        Marca: marca,
        QuienDaIngreso: quienDaIngreso,
        FW: Fw,
        CantidadesAgregar: cantidadesAgregar
    };

    inventarioData.push(filaDatos);

    limpiarFormulario();
}

function editarFila(button) {
    var row = button.parentNode.parentNode;
    var rowIndex = row.cells[0].innerHTML;

    // Actualizar el índice en el campo oculto
    document.getElementById("RowIndex").value = rowIndex;

    // Resto de tu código para llenar el formulario con los datos de la fila
    document.getElementById("CodigoProducto").value = row.cells[0].innerHTML;
    document.getElementById("Referencia").value = row.cells[1].innerHTML;
    document.getElementById("tipo").value = row.cells[2].innerHTML;
    document.getElementById("Marca").value = row.cells[3].innerHTML;
    document.getElementById("QuienDaIngreso").value = row.cells[4].innerHTML;
    document.getElementById("FW").value = row.cells[5].innerHTML;
    document.getElementById("CantidadesAgregar").value = row.cells[6].innerHTML;

    // Eliminar la fila al editar
    row.parentNode.removeChild(row);
}

function eliminarFila(button) {
    var row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function limpiarFormulario() {
    document.getElementById("CodigoProducto").value = "";
    document.getElementById("Referencia").value = "";
    document.getElementById("tipo").value = "";
    document.getElementById("Marca").value = "";
    document.getElementById("QuienDaIngreso").value = "";
    document.getElementById("FW").value = "";
    document.getElementById("CantidadesAgregar").value = "";
}

function enviarDatosAlServidor() {
    // Realizar una solicitud POST a tu script PHP
    fetch('../guardar/guardarproducto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ inventarioData: inventarioData }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
        // Puedes realizar más acciones después de recibir la respuesta del servidor
    })
    .catch(error => {
        console.error('Error al enviar datos al servidor:', error);
    });
}

// Agregar un event listener al botón "Enviar"
document.getElementById("enviarButton").addEventListener("click", function () {
    // Llamar a la función enviarDatosAlServidor al hacer clic en el botón
    enviarDatosAlServidor();
});