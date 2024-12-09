<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilos-salas.css">
    <title>TPV Salas</title>
</head>
<body>
    <form action="./mesas.php" method="POST" id="fomruarioDiv">
        <div class="container">
            <?php
                require_once "../Procesos/conection.php";  // Conexión PDO
                session_start();

                // Sesión iniciada
                if (!isset($_SESSION["camareroID"]) && !isset($_SESSION["usuarioID"])) {
                    header('Location: ../index.php?error=nosesion');
                    exit();
                } else {
                    $id_user = isset($_SESSION["camareroID"]) ? $_SESSION["camareroID"] : $_SESSION["usuarioID"];
                }

                // Consulta SQL para obtener las salas y contar las mesas libres
                $consulta = "
                    SELECT s.name_sala, 
                           COUNT(m.id_mesa) AS total_mesas, 
                           SUM(CASE WHEN h.fecha_A IS NULL THEN 1 ELSE 0 END) AS mesas_libres
                    FROM tbl_salas s
                    LEFT JOIN tbl_mesas m ON s.id_salas = m.id_sala
                    LEFT JOIN tbl_historial h ON m.id_mesa = h.id_mesa AND h.fecha_NA IS NULL
                    GROUP BY s.id_salas
                ";
                
                // Preparar y ejecutar la consulta
                $stmt = $conn->prepare($consulta);
                if ($stmt->execute()) {
                    // Obtener los resultados usando fetch() de PDO
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Generación de botones para cada sala con el conteo de mesas libres
                    if (count($resultado) > 0) {
                        foreach ($resultado as $fila) {
                            $nombre_sala = htmlspecialchars($fila['name_sala']); // Sanitizar el nombre de la sala
                            $total_mesas = $fila['total_mesas'];
                            $mesas_libres = $fila['mesas_libres'];
                            echo "<input type='submit' name='sala' value='$nombre_sala' class='input_sala input_$nombre_sala'>";
                            echo "<p class='input_sala2 mesas_disponibles_$nombre_sala'>($mesas_libres/$total_mesas)</p>";
                        }
                    } else {
                        echo "<p>No hay salas disponibles</p>";
                    }
                } else {
                    echo "<p>Error al ejecutar la consulta</p>";
                }
            ?>
        </div>
    </form>
    <div class="contenedor">
        <div class="footer">
            <a href="../Procesos/destruir.php"><button type="submit" class="logout">Cerrar Sesión</button></a>
            <?php
                if (isset($_SESSION["camareroID"])) {
                    echo '<a href="./historial.php"><button type="submit" class="back">Historial</button></a><br>';
                }

                if (isset($_SESSION["usuarioID"])) {
                    echo '<a href="./reservas.php"><button type="submit" class="back">Reservar</button></a>';
                }
            ?>
            <h1>¡Selecciona una sala para ver su disponibilidad de mesas!</h1>
        </div>
        <div class="contenedor-superior">
            <img src="../CSS/img/Mapeado/MapeadoRestaurante.png" alt="" class="mapeado">
        </div>
    </div>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../JS/alertIndex.js"></script>
</body>
</html>