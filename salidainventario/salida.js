// Función para actualizar el campo de fecha y hora del sistema
function actualizarFechaHora() {
    const fechaHoraInput = document.getElementById('fechaHora');
    const ahora = new Date();
    const opciones = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    const formateado = ahora.toLocaleString('es-ES', opciones);
    fechaHoraInput.value = formateado.replace(', ', ' ');
}

// Actualizar la fecha y hora al cargar la página y cada segundo
window.onload = function() {
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
};

// Función para cambiar el color del icono cuando se seleccionan archivos
function cambioColorIcono(inputId, iconoId) {
    const inputFile = document.getElementById(inputId);
    const icono = document.getElementById(iconoId);

    inputFile.addEventListener('change', function() {
        if (inputFile.files.length > 0) {
            icono.style.color = '#28a745';
        } else {
            icono.style.color = '';
        }
    });
}

// Llama a la función para cada input de tipo file
cambioColorIcono('evidencias', 'evidenciasLabel');
cambioColorIcono('documentacion', 'documentacionLabel');

// Función para limpiar el formulario
function limpiarFormulario() {
    document.getElementById('form-container').reset();
    document.getElementById('evidenciasLabel').style.color = '';
    document.getElementById('documentacionLabel').style.color = '';
    actualizarFechaHora();
}

// Función para validar el formulario antes de enviar
function validarFormulario(event) {
    const evidenciasInput = document.getElementById('evidencias');
    const documentacionInput = document.getElementById('documentacion');

    if (evidenciasInput.files.length === 0 || documentacionInput.files.length === 0) {
        alert('Por favor, suba todos los archivos requeridos.');
        event.preventDefault();  // Evitar que el formulario se envíe
    }
}

// Asociar la función de validación al evento de envío del formulario
document.getElementById('form-container').addEventListener('submit', validarFormulario);
