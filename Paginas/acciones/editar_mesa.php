<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_mesas WHERE id_mesa = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mesa) {
        die("Mesa no encontrada.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $n_asientos = $_POST['n_asientos'];
        $id_sala = $_POST['id_sala'];

        $sqlUpdate = "UPDATE tbl_mesas SET n_asientos = :n_asientos, id_sala = :id_sala WHERE id_mesa = :id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':n_asientos', $n_asientos);
        $stmtUpdate->bindParam(':id_sala', $id_sala);
        $stmtUpdate->bindParam(':id', $id);

        if ($stmtUpdate->execute()) {
            header("Location: ../administrar.php?success=editado_mesa");
        } else {
            echo "Error al editar la mesa.";
        }
    }
} else {
    echo "No se ha proporcionado el ID de la mesa.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/estilos-acciones.css">
    <title>Editar Mesa</title>
</head>
<body>
    <h1>Editar Mesa</h1>
    <form method="POST">
        <label for="n_asientos">Número de Asientos:</label>
        <input type="number" name="n_asientos" value="<?= htmlspecialchars($mesa['n_asientos']) ?>" required>
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
                    echo "<option value='" . $sala['id_salas'] . "' " . ($mesa['id_sala'] == $sala['id_salas'] ? 'selected' : '') . ">" . htmlspecialchars($sala['name_sala']) . "</option>";
                }
            ?>
        </select>
        <br>
        <a href="../administrar.php"><button type="button">Volver a Administrar</button></a>
        <br>
        <button type="submit">Actualizar Mesa</button>
    </form>
</body>
</html>
