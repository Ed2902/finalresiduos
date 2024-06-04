document.getElementById('Producto').addEventListener('submit', function(event) {
    event.preventDefault();

    var isValid = true;
    var inputs = document.querySelectorAll('#Producto input[required]');
    var idProductoInput = document.getElementById('id_producto');
    var usuarioInput = document.getElementById('Usuario');

    // Validar que todos los campos requeridos estén llenos
    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });


    //holaaaaa
    

    // Validar que id_producto sea un número entero
    if (!/^\d+$/.test(idProductoInput.value.trim())) {
        isValid = false;
        idProductoInput.classList.add('is-invalid');
    } else {
        idProductoInput.classList.remove('is-invalid');
    }

    // Validar que Usuario sea un número entero
    if (!/^\d+$/.test(usuarioInput.value.trim())) {
        isValid = false;
        usuarioInput.classList.add('is-invalid');
    } else {
        usuarioInput.classList.remove('is-invalid');
    }

    if (isValid) {
        // Enviar los datos del formulario
        fetch('./Guardar_producto.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Hubo un problema con la solicitud');
            }
            return response.text();
        })
        .then(data => {
            console.log('Datos guardados:', data);
            alert('Los datos fueron guardados correctamente.');
            // Redirigir a index.html después de 1 segundo
            setTimeout(() => {
                window.location.href = '../Home/home.html';
            }, 500);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al enviar los datos.');
        });
    } else {
        // Mostrar mensaje de error si la validación falla
        alert('Por favor, complete todos los campos obligatorios y asegúrese de que los campos ID Producto y Usuario sean números enteros.');
        return; // Detener la ejecución del script
    }
});
