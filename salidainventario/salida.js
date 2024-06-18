$(document).ready(function() {
    // Configurar la fecha y hora
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

    // Manejar la apertura del modal y la obtención de kilos
    $('#openModalBtn').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: './obtener_total_kilos.php',
            method: 'GET',
            success: function(response) {
                console.log('Respuesta exitosa de obtener_total_kilos.php:', response);
                $('#modalBody').html('¿Desea sacar ' + response + ' kilos del inventario?');
                $('#confirmationModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('Error al obtener el total de kilos:', error);
                $('#modalBody').html('Error al obtener el total de kilos.');
                $('#confirmationModal').modal('show');
            }
        });
    });

    // Confirmar la salida
    $('#confirmSalidaBtn').on('click', function() {
        const idSalida = $('#idSalida').val(); // Obtener idSalida desde donde corresponda
        $('#idSalida').val(idSalida);
        
        // Enviar el formulario
        $('#form-container').submit();
    });

    // Validar el formulario antes de enviar
    $('#form-container').on('submit', function(event) {
        const evidenciasInput = document.getElementById('evidencias');
        const documentacionInput = document.getElementById('documentacion');

        if (evidenciasInput.files.length === 0 || documentacionInput.files.length === 0) {
            alert('Por favor, suba todos los archivos requeridos.');
            event.preventDefault();  // Evitar que el formulario se envíe si los archivos no están adjuntos
        }
    });

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
});
