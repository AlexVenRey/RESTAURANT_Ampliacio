<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlDelete = "DELETE FROM tbl_camarero WHERE id_camarero = :id";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bindParam(':id', $id);

    if ($stmtDelete->execute()) {
        header("Location: ../administrar.php?success=eliminado");
    } else {
        echo "Error al eliminar el camarero.";
    }
} else {
    echo "No se ha proporcionado el ID del camarero.";
}
?>
