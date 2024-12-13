<?php
session_start();
require_once "../Procesos/conection.php";

// Consulta para obtener el estado de las mesas
$sql = "
    SELECT m.id_mesa, 
           m.n_asientos, 
           m.id_sala, 
           r.hora_reserva, 
           r.hora_fin, 
           h.assigned_to,
           h.fecha_A AS hora_inicio, 
           h.fecha_NA AS hora_fin_ocupacion
    FROM tbl_mesas m
    LEFT JOIN tbl_reservas r ON m.id_mesa = r.id_mesa AND r.hora_reserva <= NOW() AND r.hora_fin >= NOW()
    LEFT JOIN tbl_historial h ON m.id_mesa = h.id_mesa AND h.fecha_NA IS NULL
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$mesasEstado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Función para obtener el estado actual de la mesa
function obtenerEstadoMesa($hora_reserva, $hora_fin, $assigned_to, $hora_inicio, $hora_fin_ocupacion) {
    // Verificar si la mesa está reservada
    if ($hora_reserva && $hora_fin && strtotime($hora_reserva) <= time() && strtotime($hora_fin) > time()) {
        return 'reservada';
    }
    // Verificar si la mesa está ocupada
    if ($assigned_to) {
        return 'ocupada';
    }
    // Si no está reservada ni ocupada, está libre
    return 'libre';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/estilos-disponibilidadmesas.css">
    <title>Disponibilidad de Mesas</title>
</head>
<body>
    <header>
        <h1>Disponibilidad de Mesas</h1>
        <a href="salas.php"><button class="back" type="button">Volver a Salas</button></a>
    </header>

    <table>
        <thead>
            <tr>
                <th>Mesa</th>
                <th>Estado</th>
                <th>Nombre Asignado</th>
                <th>Fecha/Hora Inicio</th>
                <th>Fecha/Hora Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mesasEstado as $mesa): ?>
                <tr>
                    <td>Mesa <?php echo $mesa['id_mesa']; ?></td>
                    <td>
                        <?php
                        $estado = obtenerEstadoMesa($mesa['hora_reserva'], $mesa['hora_fin'], $mesa['assigned_to'], $mesa['hora_inicio'], $mesa['hora_fin_ocupacion']);
                        if ($estado === 'libre') {
                            echo '<span class="estado libre">Libre</span>';
                        } elseif ($estado === 'ocupada') {
                            echo '<span class="estado ocupada">Ocupada</span>';
                        } else {
                            echo '<span class="estado reservada">Reservada</span>';
                        }
                        ?>
                    </td>
                    <td><?php echo $mesa['assigned_to'] ? htmlspecialchars($mesa['assigned_to']) : 'Ninguno'; ?></td>
                    <td><?php echo $mesa['hora_inicio'] ? date('d/m/Y H:i', strtotime($mesa['hora_inicio'])) : 'N/A'; ?></td>
                    <td><?php echo $mesa['hora_fin_ocupacion'] ? date('d/m/Y H:i', strtotime($mesa['hora_fin_ocupacion'])) : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
