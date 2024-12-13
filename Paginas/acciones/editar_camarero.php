<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_camarero WHERE id_camarero = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $camarero = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$camarero) {
        die("Camarero no encontrado.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name_camarero'];
        $surname = $_POST['surname_camarero'];
        $username = $_POST['username_camarero'];
        $role = $_POST['roles'];

        $sqlUpdate = "UPDATE tbl_camarero SET name_camarero = :name, surname_camarero = :surname, 
                      username_camarero = :username, roles = :role WHERE id_camarero = :id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':name', $name);
        $stmtUpdate->bindParam(':surname', $surname);
        $stmtUpdate->bindParam(':username', $username);
        $stmtUpdate->bindParam(':role', $role);
        $stmtUpdate->bindParam(':id', $id);

        if ($stmtUpdate->execute()) {
            header("Location: ../administrar.php?success=editado");
        } else {
            echo "Error al editar el camarero.";
        }
    }
} else {
    echo "No se ha proporcionado el ID del camarero.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/estilos-acciones.css">
    <title>Editar Camarero</title>
    <!-- Incloure SweetAlert2 des del CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incloure el fitxer de SweetAlert2 extern -->
    <script src="../../JS/alertAcciones.js" defer></script>
</head>
<body>
    <h1>Editar Camarero</h1>
    <form method="POST" id="editForm">
        <label for="name_camarero">Nombre:</label>
        <input type="text" name="name_camarero" value="<?= htmlspecialchars($camarero['name_camarero']) ?>" required>
        <br>
        <label for="surname_camarero">Apellido:</label>
        <input type="text" name="surname_camarero" value="<?= htmlspecialchars($camarero['surname_camarero']) ?>" required>
        <br>
        <label for="username_camarero">Nombre de usuario:</label>
        <input type="text" name="username_camarero" value="<?= htmlspecialchars($camarero['username_camarero']) ?>" required>
        <br>
        <label for="roles">Rol:</label>
        <select name="roles" required>
            <option value="camarero" <?= $camarero['roles'] === 'camarero' ? 'selected' : '' ?>>Camarero</option>
            <option value="admin" <?= $camarero['roles'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <br>
        <a href="../administrar.php"><button type="button">Volver a Administrar</button></a>
        <br>
        <button type="submit" id="submitBtn">Actualizar Camarero</button>
    </form>
</body>
</html>
