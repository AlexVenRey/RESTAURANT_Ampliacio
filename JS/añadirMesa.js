document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const seatsInput = document.querySelector('[name="n_asientos"]');
    const roomSelect = document.querySelector('[name="id_sala"]');

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

        // Validación de número de asientos (no vacío)
        if (seatsInput.value.trim() === "" || seatsInput.value <= 0) {
            showError(seatsInput, 'El número de asientos no puede estar vacío o ser menor que 1.');
            valid = false;
        }

        // Validación de la sala (no vacío)
        if (roomSelect.value.trim() === "") {
            showError(roomSelect, 'Debe seleccionar una sala.');
            valid = false;
        }

        // Si algún campo no es válido, prevenimos el envío del formulario
        if (!valid) {
            event.preventDefault();  // Detener el envío del formulario
        }
    });

    // Eliminar mensajes de error cuando el usuario empieza a escribir o seleccionar
    seatsInput.addEventListener('input', function () {
        if (seatsInput.value.trim() !== "" && seatsInput.value > 0) {
            clearErrors();
        }
    });

    roomSelect.addEventListener('change', function () {
        if (roomSelect.value.trim() !== "") {
            clearErrors();
        }
    });
});
