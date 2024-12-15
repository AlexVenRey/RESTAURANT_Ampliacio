document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const nameInput = document.querySelector('[name="name_sala"]');
    const typeSelect = document.querySelector('[name="tipo_sala"]');
    
    // Función para mostrar el mensaje de error debajo de un campo
    function showError(input, message) {
        const errorSpan = document.createElement('span');
        errorSpan.classList.add('error-message');
        errorSpan.textContent = message;
        errorSpan.style.color = 'red';
        errorSpan.style.fontSize = '0.9em';
        
        // Insertar el error justo después del input
        input.insertAdjacentElement('afterend', errorSpan);
    }

    // Función para limpiar los mensajes de error
    function clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(span => {
            span.remove();  // Eliminar el mensaje de error
        });
    }

    // Validación del formulario antes de enviarlo
    form.addEventListener('submit', function (event) {
        clearErrors();  // Limpiar errores al intentar enviar el formulario
        let valid = true;

        // Validación de nombre (no vacío)
        if (nameInput.value.trim() === "") {
            showError(nameInput, 'El nombre de la sala no puede estar vacío.');
            valid = false;
        }

        // Validación de tipo de sala (no vacío)
        if (typeSelect.value.trim() === "") {
            showError(typeSelect, 'Debe seleccionar un tipo de sala.');
            valid = false;
        }

        // Si algún campo no es válido, prevenimos el envío del formulario
        if (!valid) {
            event.preventDefault();  // Detener el envío del formulario
        }
    });

    // Eliminar mensajes de error cuando el usuario empieza a escribir o seleccionar
    nameInput.addEventListener('input', function () {
        if (nameInput.value.trim() !== "") {
            clearErrors();
        }
    });
    typeSelect.addEventListener('change', function () {
        if (typeSelect.value.trim() !== "") {
            clearErrors();
        }
    });
});
