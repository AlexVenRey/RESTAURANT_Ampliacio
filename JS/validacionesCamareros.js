document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formCamareros");
    const nameInput = document.getElementById("name_camarero");
    const surnameInput = document.getElementById("surname_camarero");
    const usernameInput = document.getElementById("username_camarero");
    const passwordInput = document.getElementById("password_camarero");

    const errorName = document.createElement("span");
    const errorSurname = document.createElement("span");
    const errorUsername = document.createElement("span");
    const errorPassword = document.createElement("span");

    // Estilo para los mensajes de error
    [errorName, errorSurname, errorUsername, errorPassword].forEach((errorElement) => {
        errorElement.style.color = "red";
        errorElement.style.fontSize = "0.9em";
    });

    nameInput.insertAdjacentElement("afterend", errorName);
    surnameInput.insertAdjacentElement("afterend", errorSurname);
    usernameInput.insertAdjacentElement("afterend", errorUsername);
    passwordInput.insertAdjacentElement("afterend", errorPassword);

    form.addEventListener("submit", function (event) {
        let hasErrors = false;

        // Limpiar mensajes de error anteriores
        errorName.textContent = "";
        errorSurname.textContent = "";
        errorUsername.textContent = "";
        errorPassword.textContent = "";

        // Validaciones
        if (nameInput.value.trim().length < 3) {
            errorName.textContent = "El nombre debe tener al menos 3 caracteres.";
            hasErrors = true;
        }
        if (surnameInput.value.trim().length < 3) {
            errorSurname.textContent = "El apellido debe tener al menos 3 caracteres.";
            hasErrors = true;
        }
        if (usernameInput.value.trim().length < 3) {
            errorUsername.textContent = "El nombre de usuario debe tener al menos 3 caracteres.";
            hasErrors = true;
        }
        if (passwordInput.value.trim().length < 8) {
            errorPassword.textContent = "La contraseña debe tener al menos 8 caracteres.";
            hasErrors = true;
        } else {
            if (!/[A-Z]/.test(passwordInput.value)) {
                errorPassword.textContent = "La contraseña debe contener al menos una letra mayúscula.";
                hasErrors = true;
            }
            if (!/[a-z]/.test(passwordInput.value)) {
                errorPassword.textContent = "La contraseña debe contener al menos una letra minúscula.";
                hasErrors = true;
            }
            if (!/[0-9]/.test(passwordInput.value)) {
                errorPassword.textContent = "La contraseña debe contener al menos un número.";
                hasErrors = true;
            }
        }

        // Prevenir el envío del formulario si hay errores
        if (hasErrors) {
            event.preventDefault();
        }
    });
});
