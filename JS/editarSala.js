document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editFormSala');
    const nameInput = document.getElementById('name_sala');
    const typeSelect = document.getElementById('tipo_sala');
    const submitButton = document.getElementById('submitBtn');
    
    // Función para mostrar mensajes de error
    function showError(input, message) {
        const errorSpan = document.getElementById(`error${input.name.charAt(0).toUpperCase() + input.name.slice(1)}`);
        if (errorSpan) {
            errorSpan.textContent = message;
        }
    }

    // Función para limpiar los errores
    function clearErrors() {
        const errorSpans = document.querySelectorAll('.error-message');
        errorSpans.forEach(span => {
            span.textContent = '';
        });
    }

    // Validación de formulario antes de enviar
    form.addEventListener('submit', function (event) {
        clearErrors();
        let valid = true;

        // Validación del nombre de la sala (mínimo 3 caracteres)
        if (nameInput.value.trim() === '') {
            showError(nameInput, 'El nombre de la sala no puede estar vacío.');
            valid = false;
        } else if (nameInput.value.trim().length < 3) {
            showError(nameInput, 'El nombre de la sala debe tener al menos 3 caracteres.');
            valid = false;
        }

        // Validación del tipo de sala
        if (typeSelect.value === '') {
            showError(typeSelect, 'Selecciona un tipo de sala.');
            valid = false;
        }

        // Si algún campo es inválido, prevenimos el envío del formulario
        if (!valid) {
            event.preventDefault();
        }
    });

    // Para cuando se modifica algún campo, eliminamos el error si ya no es necesario
    nameInput.addEventListener('input', () => {
        if (nameInput.value.trim() !== '' && nameInput.value.trim().length >= 3) {
            clearErrors();
        }
    });

    typeSelect.addEventListener('change', () => {
        if (typeSelect.value !== '') {
            clearErrors();
        }
    });
});
