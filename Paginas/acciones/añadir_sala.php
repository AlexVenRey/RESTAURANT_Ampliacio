<?php
session_start();
require_once("C:/wamp64/www/DAW/M12/PROJECTE_01_AMPLIACIÓ/RESTAURANT_Ampliacio/Procesos/conection.php");

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

// Obtener los valores posibles del ENUM 'tipo_sala'
$sqlTipoSala = "SHOW COLUMNS FROM tbl_salas LIKE 'tipo_sala'";
$stmtTipoSala = $conn->prepare($sqlTipoSala);
$stmtTipoSala->execute();
$tipoSalaResult = $stmtTipoSala->fetch(PDO::FETCH_ASSOC);

// Extraer los valores del ENUM
preg_match("/^enum\((.*)\)$/", $tipoSalaResult['Type'], $matches);
$tiposSala = str_getcsv($matches[1], ",", "'");

$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name_sala'];
    $type = $_POST['tipo_sala'];

    $sql = "INSERT INTO tbl_salas (name_sala, tipo_sala) VALUES (:name, :type)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':type', $type);

    if ($stmt->execute()) {
        $success = true;
    } else {
        echo "Error al agregar la sala.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/estilos-acciones.css">
    <title>Añadir Sala</title>
    <script src="../../JS/añadirSala.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php if ($success): ?>
        <script>
            Swal.fire({
                title: '¡Sala añadida!',
                text: 'La sala ha sido añadida con éxito.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../administrar.php';
                }
            });
        </script>
    <?php endif; ?>

    <h1>Añadir Sala</h1>
    <form method="POST">
        <label for="name_sala">Nombre:</label>
        <input type="text" name="name_sala">
        <br>
        <label for="tipo_sala">Tipo:</label>
        <select name="tipo_sala" required>
            <?php foreach ($tiposSala as $tipo): ?>
                <option value="<?= htmlspecialchars($tipo) ?>">
                    <?= htmlspecialchars($tipo) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <a href="../administrar.php"><button type="button">Volver a Administrar</button></a>
        <br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
