// Esta función se ejecutará cuando se seleccione un archivo en un input de tipo file
function cambioColorIcono(inputId, iconoId) {
    // Selecciona el input de tipo file y el icono según los IDs proporcionados
    const inputFile = document.getElementById(inputId);
    const icono = document.getElementById(iconoId);

    // Verifica si se seleccionó un archivo
    inputFile.addEventListener('change', function() {
        // Si hay un archivo seleccionado, cambia el color del icono a verde
        if (inputFile.files.length > 0) {
            icono.style.color = '#28a745';
        } else {
            // Si no hay archivo seleccionado, restaura el color original del icono
            icono.style.color = '';
        }
    });
}

// Función para cambiar el color del icono cuando se selecciona una fecha
function cambioColorIconoFecha(inputId, iconoId) {
    // Selecciona el input de fecha y el icono según los IDs proporcionados
    const inputFecha = document.getElementById(inputId);
    const iconoFecha = document.getElementById(iconoId);

    // Verifica si se seleccionó una fecha
    inputFecha.addEventListener('change', function() {
        // Si hay una fecha seleccionada, cambia el color del icono a verde
        if (inputFecha.value) {
            iconoFecha.style.color = '#28a745';
        } else {
            // Si no hay fecha seleccionada, restaura el color original del icono
            iconoFecha.style.color = '';
        }
    });
}

// Llama a la función para cada input de tipo file que desees que cambie de color
cambioColorIcono('Camara', 'camaraLabel');
cambioColorIcono('rut', 'rutLabel');
cambioColorIcono('CC', 'CCLabel');
cambioColorIcono('comercial', 'comercialLabel');
cambioColorIcono('bancaria', 'bancariaLabel');
cambioColorIcono('circular', 'circularLabel');
cambioColorIcono('seguridad', 'seguridadLabel');
cambioColorIcono('financieros', 'financierosLabel');
cambioColorIcono('autorizacion', 'autorizacionLabel');
cambioColorIcono('visita', 'visitaLabel');
cambioColorIcono('antecedentes', 'antecedentesLabel');

// Llama a la función para el input de fecha
cambioColorIconoFecha('fecha', 'fechaLabel');



function validarFormulario() {
    var campos = document.querySelectorAll('#cliente input, #cliente select, #cliente textarea');
    var todosLlenos = true;

    campos.forEach(function (campo) {
        // Excluir los campos financieros y visita de la validación
        if (campo.id !== 'financieros' && campo.id !== 'visita') {
            if (campo.value.trim() === '') {
                campo.style.borderColor = 'red';
                todosLlenos = false;
            } else {
                campo.style.borderColor = '';
                // Cambiar el color del campo a negro si ya tiene algún valor y está enfocado
                campo.style.color = '#000'; // Negro
            }

            // Cambiar el color del campo a negro cuando se enfoca si ya tiene algún valor
            campo.addEventListener('focus', function() {
                if (campo.value.trim() !== '') {
                    campo.style.color = '#000'; // Negro
                }
            });
        }
    });

    if (!todosLlenos) {
        alert('Por favor, llene todos los campos obligatorios.');
        event.preventDefault();
    } else {
        // Si todos los campos están llenos, mostramos un mensaje de éxito
        alert('El formulario fue enviado con éxito.');
    }
}