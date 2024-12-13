<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlDelete = "DELETE FROM tbl_salas WHERE id_salas = :id";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bindParam(':id', $id);

    if ($stmtDelete->execute()) {
        header("Location: ../administrar.php?success=eliminado_sala");
    } else {
        echo "Error al eliminar la sala.";
    }
} else {
    echo "No se ha proporcionado el ID de la sala.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/estilos-acciones.css">
    <title>Eliminar Sala</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../JS/alertAcciones.js" defer></script>
</head>
<body>
    <h1>Eliminar Sala</h1>
    <form method="POST" id="deleteSalaForm">
        <input type="hidden" name="id_sala" value="<?= htmlspecialchars($_GET['id']); ?>">
        <p>¿Estás seguro de que deseas eliminar esta sala?</p>
        <a href="../administrar.php"><button type="button">Volver a Administrar</button></a>
        <br>
        <button type="submit" id="submitBtn">Eliminar Sala</button>
    </form>
</body>
</html>
