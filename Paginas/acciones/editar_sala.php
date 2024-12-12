<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_salas WHERE id_salas = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $sala = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sala) {
        die("Sala no encontrada.");
    }

    // Obtener los valores posibles del ENUM 'tipo_sala'
    $sqlTipoSala = "SHOW COLUMNS FROM tbl_salas LIKE 'tipo_sala'";
    $stmtTipoSala = $conn->prepare($sqlTipoSala);
    $stmtTipoSala->execute();
    $tipoSalaResult = $stmtTipoSala->fetch(PDO::FETCH_ASSOC);

    // Extraer los valores del ENUM
    preg_match("/^enum\((.*)\)$/", $tipoSalaResult['Type'], $matches);
    $tiposSala = str_getcsv($matches[1], ",", "'");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name_sala'];
        $type = $_POST['tipo_sala'];

        $sqlUpdate = "UPDATE tbl_salas SET name_sala = :name, tipo_sala = :type WHERE id_salas = :id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':name', $name);
        $stmtUpdate->bindParam(':type', $type);
        $stmtUpdate->bindParam(':id', $id);

        if ($stmtUpdate->execute()) {
            header("Location: ../administrar.php?success=editado_sala");
        } else {
            echo "Error al editar la sala.";
        }
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
    <title>Editar Sala</title>
</head>
<body>
    <h1>Editar Sala</h1>
    <form method="POST">
        <label for="name_sala">Nombre:</label>
        <input type="text" name="name_sala" value="<?= htmlspecialchars($sala['name_sala']) ?>" required>
        <br>
        <label for="tipo_sala">Tipo:</label>
        <select name="tipo_sala" required>
            <?php foreach ($tiposSala as $tipo): ?>
                <option value="<?= htmlspecialchars($tipo) ?>" <?= $tipo == $sala['tipo_sala'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tipo) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <a href="../administrar.php"><button type="button">Volver a Administrar</button></a>
        <br>
        <button type="submit">Actualizar Sala</button>
    </form>
</body>
</html>
