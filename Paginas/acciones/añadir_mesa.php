<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $n_asientos = $_POST['n_asientos'];
    $id_sala = $_POST['id_sala'];

    $sql = "INSERT INTO tbl_mesas (n_asientos, id_sala) VALUES (:n_asientos, :id_sala)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':n_asientos', $n_asientos);
    $stmt->bindParam(':id_sala', $id_sala);

    if ($stmt->execute()) {
        header("Location: ../administrar.php?success=añadido_mesa");
    } else {
        echo "Error al agregar la mesa.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/estilos-acciones.css">
    <title>Añadir Mesa</title>
</head>
<body>
    <h1>Añadir Mesa</h1>
    <form method="POST">
        <label for="n_asientos">Número de Asientos:</label>
        <input type="number" name="n_asientos" required>
        <br>
        <label for="id_sala">Sala:</label>
        <select name="id_sala" required>
            <!-- Aquí puedes añadir las opciones de salas dinámicamente -->
            <?php
                $sqlSalas = "SELECT id_salas, name_sala FROM tbl_salas";
                $stmtSalas = $conn->prepare($sqlSalas);
                $stmtSalas->execute();
                $salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);
                foreach ($salas as $sala) {
                    echo "<option value='" . $sala['id_salas'] . "'>" . htmlspecialchars($sala['name_sala']) . "</option>";
                }
            ?>
        </select>
        <br>
        <a href="../administrar.php"><button type="button">Volver a Administrar</button></a>
        <br>
        <button type="submit">Añadir Mesa</button>
    </form>
</body>
</html>
