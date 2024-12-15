<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name_camarero'];
    $surname = $_POST['surname_camarero'];
    $username = $_POST['username_camarero'];
    $role = $_POST['roles'];

    $sql = "INSERT INTO tbl_camarero (name_camarero, surname_camarero, username_camarero, roles) 
            VALUES (:name, :surname, :username, :role)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        $success = true;
    } else {
        echo "Error al agregar el camarero.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/estilos-acciones.css">
    <script src="../../JS/añadirCamarero.js"></script>
    <title>Añadir Camarero</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php if ($success): ?>
        <script>
            Swal.fire({
                title: '¡Camarero añadido!',
                text: 'El camarero ha sido añadido con éxito.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../administrar.php';
                }
            });
        </script>
    <?php endif; ?>

    <h1>Añadir Camarero</h1>
    <form method="POST" id="camareroForm">
        <label for="name_camarero">Nombre:</label>
        <input type="text" name="name_camarero" id="name_camarero">
        <span id="errorName" class="error-message"></span> <!-- Aquí aparecerá el mensaje de error -->
        <br>

        <label for="surname_camarero">Apellido:</label>
        <input type="text" name="surname_camarero" id="surname_camarero">
        <span id="errorSurname" class="error-message"></span> <!-- Aquí aparecerá el mensaje de error -->
        <br>

        <label for="username_camarero">Nombre de usuario:</label>
        <input type="text" name="username_camarero" id="username_camarero">
        <span id="errorUsername" class="error-message"></span> <!-- Aquí aparecerá el mensaje de error -->
        <br>

        <label for="roles">Rol:</label>
        <select name="roles" id="roles">
            <option value="camarero">Camarero</option>
            <option value="admin">Admin</option>
        </select>
        <br>
        
        <a href="../administrar.php"><button type="button">Volver a Administrar</button></a>
        <br>
        <button type="submit">Guardar</button>
        <br>
    </form>
</body>
</html>
