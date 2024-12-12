<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilos-salas.css">
    <title>TPV Salas</title>
</head>
<body>
    <header class="header">
        <a href="../Procesos/destruir.php"><button type="button" class="logout">Cerrar Sesión</button></a>
        <a href="./historial"><button type="button" class="back">Historial</button></a>
        <a href="./reservar"><button type="button" class="back">Reservas</button></a>
        <?php
        session_start();
        if (!isset($_SESSION["camareroID"])) {
            header('Location: ../index.php?error=nosesion');
            exit();
        }

        // Mostrar el botón Administrar solo si el rol es admin
        if ($_SESSION["rol"] === "admin") {
            echo '<a href="./administrar.php"><button type="button" class="back">Administrar</button></a>';
        }
        ?>
    </header>

    <main>
        <form action="./mesas.php" method="POST" id="formularioSalas">
            <?php
                require_once "../Procesos/conection.php";

                $id_user = $_SESSION["camareroID"];

                try {
                    $consulta = "
                        SELECT s.name_sala, 
                               COUNT(m.id_mesa) AS total_mesas, 
                               SUM(CASE WHEN h.fecha_A IS NULL THEN 1 ELSE 0 END) AS mesas_libres
                        FROM tbl_salas s
                        LEFT JOIN tbl_mesas m ON s.id_salas = m.id_sala
                        LEFT JOIN tbl_historial h ON m.id_mesa = h.id_mesa AND h.fecha_NA IS NULL
                        GROUP BY s.id_salas
                    ";
                    $stmt = $conn->prepare($consulta);
                    $stmt->execute();

                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($resultado) > 0) {
                        foreach ($resultado as $fila) {
                            $nombre_sala = htmlspecialchars($fila['name_sala']);
                            $total_mesas = $fila['total_mesas'];
                            $mesas_libres = $fila['mesas_libres'];
                            echo "
                                <div class='sala-item'>
                                    <button type='submit' name='sala' value='$nombre_sala' class='btn-sala'>$nombre_sala</button>
                                    <p class='info-mesas'>($mesas_libres/$total_mesas mesas libres)</p>
                                </div>
                            ";
                        }
                    } else {
                        echo "<p>No hay salas disponibles</p>";
                    }
                } catch (Exception $e) {
                    echo "<p>Error al ejecutar la consulta: " . $e->getMessage() . "</p>";
                }
            ?>
        </form>
    </main>
</body>
</html>