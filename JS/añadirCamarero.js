document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#camareroForm');
    const nameInput = document.querySelector('#name_camarero');
    const surnameInput = document.querySelector('#surname_camarero');
    const usernameInput = document.querySelector('#username_camarero');
    const roleSelect = document.querySelector('#roles');

    // Función para mostrar el mensaje de error debajo de un campo
    function showError(input, message) {
        // Crear un nuevo span para el error si no existe ya
        let errorSpan = input.nextElementSibling; // Buscar el siguiente hermano del input

        if (!errorSpan || !errorSpan.classList.contains('error-message')) {
            errorSpan = document.createElement('span');
            errorSpan.classList.add('error-message');
            input.parentNode.insertBefore(errorSpan, input.nextSibling); // Insertar el mensaje de error debajo del input
        }

        errorSpan.textContent = message; // Establecer el mensaje de error
        errorSpan.style.color = 'red';
        errorSpan.style.fontSize = '0.9em';
    }

    // Función para limpiar los mensajes de error
    function clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(span => {
            span.textContent = '';  // Limpiar mensaje de error
        });
    }

    // Validación del formulario antes de enviarlo
    form.addEventListener('submit', function (event) {
        clearErrors();  // Limpiar errores al intentar enviar el formulario
        let valid = true;

        // Validación de nombre (no vacío y mínimo 3 caracteres)
        if (nameInput.value.trim() === "") {
            showError(nameInput, 'El nombre no puede estar vacío.');
            valid = false;
        } else if (nameInput.value.trim().length < 3) {
            showError(nameInput, 'El nombre debe tener al menos 3 caracteres.');
            valid = false;
        }

        // Validación de apellido (no vacío y mínimo 3 caracteres)
        if (surnameInput.value.trim() === "") {
            showError(surnameInput, 'El apellido no puede estar vacío.');
            valid = false;
        } else if (surnameInput.value.trim().length < 3) {
            showError(surnameInput, 'El apellido debe tener al menos 3 caracteres.');
            valid = false;
        }

        // Validación de nombre de usuario (no vacío y mínimo 3 caracteres)
        if (usernameInput.value.trim() === "") {
            showError(usernameInput, 'El nombre de usuario no puede estar vacío.');
            valid = false;
        } else if (usernameInput.value.trim().length < 3) {
            showError(usernameInput, 'El nombre de usuario debe tener al menos 3 caracteres.');
            valid = false;
        }

        // Si algún campo no es válido, prevenimos el envío del formulario
        if (!valid) {
            event.preventDefault();  // Detener el envío del formulario
        }
    });

    // Eliminar mensajes de error cuando el usuario empieza a escribir
    nameInput.addEventListener('input', function () {
        if (nameInput.value.trim().length >= 3) {
            clearErrors();
        }
    });

    surnameInput.addEventListener('input', function () {
        if (surnameInput.value.trim().length >= 3) {
            clearErrors();
        }
    });

    usernameInput.addEventListener('input', function () {
        if (usernameInput.value.trim().length >= 3) {
            clearErrors();
        }
    });
});
