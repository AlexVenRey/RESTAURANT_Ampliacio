<?php
// Iniciar sesión
session_start();

// Verificar si el usuario tiene una sesión activa
if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

// Conexión a la base de datos
require_once "../Procesos/conection.php";

// Manejo de la inserción de un nuevo camarero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_camarero'])) {
    $name = $_POST['name_camarero'];
    $surname = $_POST['surname_camarero'];
    $username = $_POST['username_camarero'];
    $password = $_POST['password_camarero'];

    // Validación básica
    if (!empty($name) && !empty($surname) && !empty($username) && !empty($password)) {

        // Comprobar si el nombre, apellido o nombre de usuario ya existen
        $query_check = "SELECT * FROM tbl_camarero WHERE name_camarero = ? OR surname_camarero = ? OR username_camarero = ?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("sss", $name, $surname, $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $message = "<p style='color: red;'>El nombre, apellido o nombre de usuario ya existen.</p>";
        } else {
            // Encriptar la contraseña
            $hashed_password = hash('sha256', $password);

            // Preparar la consulta SQL para insertar el nuevo camarero
            $query = "INSERT INTO tbl_camarero (name_camarero, surname_camarero, username_camarero, pwd_camarero) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($query);
            $stmt_insert->bind_param("ssss", $name, $surname, $username, $hashed_password);

            // Ejecutar la consulta
            if ($stmt_insert->execute()) {
                $message = "<p style='color: green;'>Camarero agregado correctamente.</p>";
            } else {
                $message = "<p style='color: red;'>Error al agregar el camarero.</p>";
            }

            // Cerrar la declaración
            $stmt_insert->close();
        }

        // Cerrar la declaración de verificación
        $stmt_check->close();
    } else {
        $message = "<p style='color: red;'>Todos los campos son obligatorios.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilos-camareros.css">
    <script src="../JS/validacionesCamareros.js"></script>
    <title>Gestión de Camareros</title>
</head>
<body>
    <h1>Gestión de Camareros</h1>

    <!-- Formulario para agregar camareros -->
    <h2>Agregar nuevo camarero</h2>
    <?php if (isset($message)) echo $message; ?>
    <form method="POST" action="Camareros.php" id="formCamareros">
        <label for="name_camarero">Nombre:</label>
        <input type="text" id="name_camarero" name="name_camarero">
        <br>
        <label for="surname_camarero">Apellido:</label>
        <input type="text" id="surname_camarero" name="surname_camarero">
        <br>
        <label for="username_camarero">Nombre de usuario:</label>
        <input type="text" id="username_camarero" name="username_camarero">
        <br>
        <label for="password_camarero">Contraseña:</label>
        <input type="password" id="password_camarero" name="password_camarero">
        <br>
        <button type="submit" name="add_camarero">Agregar Camarero</button>
    </form>

    <!-- Listado de camareros -->
    <h2>Listado de camareros</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Nombre de Usuario</th>
                <th>Contraseña</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consultar todos los camareros
            $query = "SELECT id_camarero, name_camarero, surname_camarero, username_camarero, pwd_camarero FROM tbl_camarero";
            $stmt = $conn->prepare($query);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_camarero']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name_camarero']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['surname_camarero']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username_camarero']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pwd_camarero']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay camareros registrados.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Error al obtener la lista de camareros.</td></tr>";
            }

            // Cerrar la declaración
            $stmt->close();
            ?>
        </tbody>
    </table>
    <br>
    <!-- Botón de volver -->
    <a href="./salas.php"><button>Volver a Salas</button></a>

    <!-- Botón de cerrar sesión -->
    <a href="../Procesos/destruir.php"><button>Cerrar Sesión</button></a>
</body>
</html>
