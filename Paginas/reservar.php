<?php
session_start();
require_once "../Procesos/conection.php";

// Procesar la reserva
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['fecha'], $_POST['hora'], $_POST['sala'], $_POST['mesa'])) {
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $sala = $_POST['sala'];
        $mesa = $_POST['mesa'];

        // Calcular la hora de fin (2 horas después)
        $horaInicio = strtotime($fecha . ' ' . $hora); // Combina la fecha y hora de inicio
        $horaFin = strtotime('+2 hours', $horaInicio); // Sumar 2 horas

        // Convertir a formato Y-m-d H:i:s para almacenar en la base de datos
        $horaInicioFormatted = date('Y-m-d H:i:s', $horaInicio);
        $horaFinFormatted = date('Y-m-d H:i:s', $horaFin);

        // Verificar si la mesa ya está reservada en el rango de tiempo seleccionado
        $sqlVerificarReserva = "SELECT * FROM tbl_reservas WHERE id_mesa = :mesa 
                                AND (hora_reserva BETWEEN :hora_inicio AND :hora_fin 
                                OR hora_fin BETWEEN :hora_inicio AND :hora_fin)";
        $stmtVerificar = $conn->prepare($sqlVerificarReserva);
        $stmtVerificar->bindParam(':mesa', $mesa, PDO::PARAM_INT);
        $stmtVerificar->bindParam(':hora_inicio', $horaInicioFormatted, PDO::PARAM_STR);
        $stmtVerificar->bindParam(':hora_fin', $horaFinFormatted, PDO::PARAM_STR);
        $stmtVerificar->execute();

        if ($stmtVerificar->rowCount() > 0) {
            // La mesa ya está reservada
            echo "<p class='text-danger'>La mesa ya está reservada en el horario seleccionado.</p>";
        } else {
            // Si no está reservada, realizar la reserva
            $sqlReservar = "INSERT INTO tbl_reservas (id_mesa, hora_reserva, hora_fin) 
                            VALUES (:mesa, :hora_reserva, :hora_fin)";
            $stmtReservar = $conn->prepare($sqlReservar);
            $stmtReservar->bindParam(':mesa', $mesa, PDO::PARAM_INT);
            $stmtReservar->bindParam(':hora_reserva', $horaInicioFormatted, PDO::PARAM_STR);
            $stmtReservar->bindParam(':hora_fin', $horaFinFormatted, PDO::PARAM_STR);
            $stmtReservar->execute();

            // Después de insertar la reserva, actualizar el estado de la mesa
            $sqlActualizarEstado = "UPDATE tbl_mesas SET estado = 'reservada' WHERE id_mesa = :mesa";
            $stmtActualizarEstado = $conn->prepare($sqlActualizarEstado);
            $stmtActualizarEstado->bindParam(':mesa', $mesa, PDO::PARAM_INT);
            $stmtActualizarEstado->execute();

            echo "<p class='text-success'>La mesa ha sido reservada con éxito.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilos-reservas.css">
    <title>Reservas</title>
</head>
<body>
    <header class="header">
        <a href="./salas.php"><button type="button" class="back">Volver a salas</button></a>
        <a href="./disponibilidad_mesas.php"><button type="button" class="back">Disponibilidad mesas</button></a>
    </header>
    <form class="formReserva" method="POST" action="">
        <h1>Reserva una mesa</h1>

        <!-- Fecha -->
        <label for="fecha">Selecciona la fecha para la reserva:</label>
        <input 
            type="date" 
            id="fecha" 
            name="fecha" 
            min="<?php echo date('Y-m-d'); ?>" 
            value="<?php echo isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : ''; ?>">
        <br>

        <!-- Hora -->
        <label for="hora">Selecciona la hora de la reserva:</label>
        <select id="hora" name="hora">
            <option disabled <?php echo !isset($_POST['hora']) ? 'selected' : ''; ?>>Selecciona una hora:</option>
            <?php
            for ($hour = 0; $hour < 24; $hour++) {
                $horaFormatted = str_pad($hour, 2, "0", STR_PAD_LEFT) . ":00";
                $selected = (isset($_POST['hora']) && $_POST['hora'] === $horaFormatted) ? 'selected' : '';
                echo "<option value='$horaFormatted' $selected>$horaFormatted</option>";
            }
            ?>
        </select>
        <br>

        <!-- Sala -->
        <label for="sala">Sala:</label>
        <select id="sala" name="sala" onchange="this.form.submit()">
            <option disabled <?php echo !isset($_POST['sala']) ? 'selected' : ''; ?>>Selecciona una sala:</option>
            <?php
            $sqlSala = "SELECT name_sala FROM tbl_salas";
            $stmtSala = $conn->prepare($sqlSala);
            $stmtSala->execute();
            $salas = $stmtSala->fetchAll(PDO::FETCH_ASSOC);

            foreach ($salas as $sala) {
                $selected = (isset($_POST['sala']) && $_POST['sala'] === $sala['name_sala']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($sala['name_sala']) . "' $selected>" . htmlspecialchars($sala['name_sala']) . "</option>";
            }
            ?>
        </select>
        <br>

        <!-- Mesa -->
        <label for="mesa">Mesa:</label>
        <select id="mesa" name="mesa">
            <option disabled <?php echo !isset($_POST['mesa']) ? 'selected' : ''; ?>>Selecciona una mesa:</option>
            <?php
            if (isset($_POST['sala'])) {
                $salaSeleccionada = htmlspecialchars($_POST['sala']);
                $sqlMesa = "SELECT id_mesa FROM tbl_mesas WHERE id_sala = (SELECT id_salas FROM tbl_salas WHERE name_sala = :sala)";
                $stmtMesa = $conn->prepare($sqlMesa);
                $stmtMesa->bindParam(':sala', $salaSeleccionada, PDO::PARAM_STR);
                $stmtMesa->execute();
                $mesas = $stmtMesa->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($mesas)) {
                    foreach ($mesas as $mesa) {
                        $sqlComprobarReserva = "SELECT * FROM tbl_reservas WHERE id_mesa = :mesa";
                        $stmtComprobar = $conn->prepare($sqlComprobarReserva);
                        $stmtComprobar->bindParam(':mesa', $mesa['id_mesa'], PDO::PARAM_INT);
                        $stmtComprobar->execute();
                        $estadoMesa = $stmtComprobar->rowCount() > 0 ? "ocupada" : "disponible";
                        // Agregar atributo "disabled" si la mesa está ocupada
                        $disabled = $estadoMesa === "ocupada" ? "disabled" : "";
                        echo "<option value='" . htmlspecialchars($mesa['id_mesa']) . "' $disabled>Mesa " . htmlspecialchars($mesa['id_mesa']) . " ($estadoMesa)</option>";
                    }
                } else {
                    echo "<option disabled>No hay mesas disponibles</option>";
                }
            }
            ?>
        </select>
        <br>

        <!-- Asignar nombre -->
        <label for="assigned_to">Asignar nombre:</label>
        <input type="text" id="assigned_to" name="assigned_to" placeholder="Nombre de la reserva">
        <br>

        <input type="submit" value="Reservar mesa">
    </form>
</body>
</html>