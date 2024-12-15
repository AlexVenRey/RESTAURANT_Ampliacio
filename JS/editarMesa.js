document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editFormMesa');
    const nAsientosInput = document.getElementById('n_asientos');
    
    // Función para mostrar mensajes de error
    function showError(input, message) {
        const errorSpan = document.getElementById(`error${input.name.charAt(0).toUpperCase() + input.name.slice(1)}`);
        if (errorSpan) {
            errorSpan.textContent = message;  // Mostrar mensaje de error
        }
    }

    // Función para limpiar los errores
    function clearErrors() {
        const errorSpans = document.querySelectorAll('.error-message');
        errorSpans.forEach(span => {
            span.textContent = '';  // Limpiar mensaje de error
        });
    }

    // Validación de formulario antes de enviar
    form.addEventListener('submit', function (event) {
        clearErrors();  // Limpiar los errores al intentar enviar el formulario
        let valid = true;

        // Validación del número de asientos (no puede estar vacío y debe ser mayor o igual a 2)
        if (nAsientosInput.value.trim() === "") {
            showError(nAsientosInput, 'El campo de número de asientos no puede estar vacío.');
            valid = false;
        } else if (nAsientosInput.value < 2) {
            showError(nAsientosInput, 'Una mesa debe contener mínimo 2 asientos.');
            valid = false;
        }

        // Si algún campo es inválido, prevenimos el envío del formulario
        if (!valid) {
            event.preventDefault();  // No enviar el formulario
        } else {
            console.log('Formulario válido');
        }
    });

    // Para cuando se modifica el número de asientos, eliminamos el error si ya no está vacío o es menor que 2
    nAsientosInput.addEventListener('input', () => {
        if (nAsientosInput.value.trim() !== "" && nAsientosInput.value >= 2) {
            clearErrors();  // Limpiar errores al corregir el valor
        }
    });
});
