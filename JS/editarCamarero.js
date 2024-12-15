document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("editForm");
    const submitBtn = document.getElementById("submitBtn");

    // Función para limpiar los mensajes de error
    function clearErrors() {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(function(msg) {
            msg.textContent = "";
        });
    }

    form.addEventListener("submit", function(event) {
        clearErrors(); // Limpiar los errores antes de validar
        // Evitar el envío del formulario si la validación falla
        if (!validateForm()) {
            event.preventDefault();
        }
    });

    function validateForm() {
        const name = document.getElementById("name_camarero").value.trim();
        const surname = document.getElementById("surname_camarero").value.trim();
        const username = document.getElementById("username_camarero").value.trim();

        let isValid = true;

        // Validar el nombre
        if (name.length < 3) {
            document.getElementById("nameError").textContent = "El nombre debe tener al menos 3 caracteres.";
            isValid = false;
        }

        // Validar el apellido
        if (surname.length < 3) {
            document.getElementById("surnameError").textContent = "El apellido debe tener al menos 3 caracteres.";
            isValid = false;
        }

        // Validar el nombre de usuario
        if (username.length < 3) {
            document.getElementById("usernameError").textContent = "El nombre de usuario debe tener al menos 3 caracteres.";
            isValid = false;
        }

        return isValid;
    }
});
