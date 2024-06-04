function abrirModal() {
    document.getElementById("myModal").style.display = "block";
    // Agregar la clase modal-open al cuerpo para ocultar la barra de desplazamiento
    document.body.classList.add("modal-open");
  }

  function cerrarModal() {
    document.getElementById("myModal").style.display = "none";
    // Remover la clase modal-open al cerrar el modal
    document.body.classList.remove("modal-open");
  }

//validadr datos 

// Define un arreglo para almacenar los datos
var datosGuardados = [];

function cerrarModal() {
  document.getElementById('myModal').style.display = 'none';
}

function enviarDatosAdicionales() {
  // Obtener los valores de los campos
  var empresaCliente = document.getElementById('EmpresaCliente').value;
  var ccReclama = document.getElementById('CCReclama').value;
  var placa = document.getElementById('Placa').value;
  var telefonoRecoge = document.getElementById('TelefonoRecoge').value;
  var horaRecogida = document.getElementById('HoraRecogida').value;

  // Validar que todos los campos estén completos
  if (!empresaCliente || !ccReclama || !placa || !telefonoRecoge || !horaRecogida) {
    alert('Todos los campos son obligatorios. Por favor, complete todos los campos.');
    return;
  }

  // Validar que el teléfono tenga exactamente 10 dígitos
  if (telefonoRecoge.length !== 10) {
    alert('El teléfono debe contener exactamente 10 dígitos.');
    return;
  }

  // Almacenar los datos en un objeto
  var datos = {
    empresaCliente: empresaCliente,
    ccReclama: ccReclama,
    placa: placa,
    telefonoRecoge: telefonoRecoge,
    horaRecogida: horaRecogida
  };

  // Agregar los datos al arreglo
  datosGuardados.push(datos);

  // Puedes hacer lo que quieras con los datos aquí
  console.log(datosGuardados);

  // Limpiar los campos del formulario
  limpiarFormulario();

  // Puedes cerrar el modal después de procesar los datos
  cerrarModal();

  // Aquí puedes enviar los datos a través de AJAX a tu servidor si lo necesitas.
}

function limpiarFormulario() {
  // Limpiar los campos del formulario
  document.getElementById('EmpresaCliente').value = '';
  document.getElementById('CCReclama').value = '';
  document.getElementById('Placa').value = '';
  document.getElementById('TelefonoRecoge').value = '';
  document.getElementById('HoraRecogida').value = '';
}