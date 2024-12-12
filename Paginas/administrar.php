<?php
session_start();

require_once "../Procesos/conection.php";

if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php?error=nosesion');
    exit();
}

try {
    // Consulta para obtener los datos de los camareros
    $sqlCamareros = "SELECT id_camarero, name_camarero, surname_camarero, username_camarero, roles FROM tbl_camarero";
    $stmtCamareros = $conn->prepare($sqlCamareros);
    $stmtCamareros->execute();
    $camareros = $stmtCamareros->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener los datos de las salas
    $sqlSalas = "SELECT id_salas, name_sala, tipo_sala FROM tbl_salas";
    $stmtSalas = $conn->prepare($sqlSalas);
    $stmtSalas->execute();
    $salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener los datos de las mesas
    $sqlMesas = "SELECT tbl_mesas.id_mesa, tbl_mesas.n_asientos, tbl_salas.name_sala FROM tbl_mesas 
                 INNER JOIN tbl_salas ON tbl_mesas.id_sala = tbl_salas.id_salas";
    $stmtMesas = $conn->prepare($sqlMesas);
    $stmtMesas->execute();
    $mesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Restaurante</title>
    <link rel="stylesheet" href="../CSS/estilos-administrar.css">
</head>
<body>
    <header class="header">
        <a href="./salas.php"><button type="button" class="back">Volver</button></a>
    </header>    
    
    <br>
    
    <h1>Administración del Restaurante</h1>

    <!-- Tabla de Camareros -->
    <h2>Lista de Camareros</h2>
    <?php if (count($camareros) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Nombre de usuario</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($camareros as $camarero): ?>
                    <tr>
                        <td><?= htmlspecialchars($camarero['id_camarero']) ?></td>
                        <td><?= htmlspecialchars($camarero['name_camarero']) ?></td>
                        <td><?= htmlspecialchars($camarero['surname_camarero']) ?></td>
                        <td><?= htmlspecialchars($camarero['username_camarero']) ?></td>
                        <td><?= htmlspecialchars($camarero['roles']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay camareros registrados.</p>
    <?php endif; ?>

    <!-- Tabla de Salas -->
    <h2>Lista de Salas</h2>
    <?php if (count($salas) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salas as $sala): ?>
                    <tr>
                        <td><?= htmlspecialchars($sala['id_salas']) ?></td>
                        <td><?= htmlspecialchars($sala['name_sala']) ?></td>
                        <td><?= htmlspecialchars($sala['tipo_sala']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay salas registradas.</p>
    <?php endif; ?>

    <!-- Tabla de Mesas -->
    <h2>Lista de Mesas</h2>
    <?php if (count($mesas) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Mesa</th>
                    <th>Número de Asientos</th>
                    <th>Sala</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mesas as $mesa): ?>
                    <tr>
                        <td><?= htmlspecialchars($mesa['id_mesa']) ?></td>
                        <td><?= htmlspecialchars($mesa['n_asientos']) ?></td>
                        <td><?= htmlspecialchars($mesa['name_sala']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay mesas registradas.</p>
    <?php endif; ?>

</body>
</html>
