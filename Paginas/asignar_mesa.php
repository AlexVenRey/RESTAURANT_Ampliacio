<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- css -->
    <link rel="stylesheet" href="../CSS/estilos-asignar.css">
</head>
<body>
    <div class="container text-center">
        <?php
            require_once "../Procesos/conection.php";  // Conexión PDO
            session_start();

            // Comprobación de sesión activa
            if (!isset($_SESSION["camareroID"]) && !isset($_SESSION['usuarioID']) && !isset($_SESSION['sala'])) {
                header('Location: ../index.php');
                exit();
            }

            if (isset($_SESSION["camareroID"])) {
                $id_user = $_SESSION["camareroID"];
            } elseif (isset($_SESSION["usuarioID"])) {
                $id_user = $_SESSION["usuarioID"];
            }
            
            if (isset($_POST['mesa'])) {
                $id_mesa = $_POST['mesa'];

                // Verificar si se ha solicitado desasignar la mesa
                if (isset($_POST['desasignar'])) {
                    $stmt_update = $conn->prepare("UPDATE tbl_historial SET fecha_NA = NOW() WHERE id_mesa = ? AND fecha_NA IS NULL");
                    $stmt_update->bindValue(1, $id_mesa, PDO::PARAM_INT);
                    $stmt_update->execute();

                    if ($stmt_update->rowCount() > 0) {
                        echo "<p class='text-success'>Mesa $id_mesa desasignada exitosamente.</p>";
                    } else {
                        echo "<p class='text-danger'>Error al desasignar la mesa. Intenta de nuevo.</p>";
                    }

                    // Cerrar cursor
                    $stmt_update->closeCursor();
                }

                // Verificar si se ha solicitado asignar la mesa y validar el campo 'assigned_to'
                if (isset($_POST['assigned_to']) && $_POST['assigned_to'] !== '') {
                    $assigned_to = $_POST['assigned_to'];

                    // Validación de al menos 3 caracteres y solo letras
                    if (preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,}$/", $assigned_to)) {
                        $stmt_insert = $conn->prepare("INSERT INTO tbl_historial (fecha_A, assigned_by, assigned_to, id_mesa) VALUES (NOW(), ?, ?, ?)");
                        $stmt_insert->bindValue(1, $id_user, PDO::PARAM_INT);
                        $stmt_insert->bindValue(2, $assigned_to, PDO::PARAM_STR);
                        $stmt_insert->bindValue(3, $id_mesa, PDO::PARAM_INT);
                        $stmt_insert->execute();

                        if ($stmt_insert->rowCount() > 0) {
                            echo "<p class='text-success'>Mesa $id_mesa asignada exitosamente a $assigned_to.</p>";
                        } else {
                            echo "<p class='text-danger'>Error al asignar la mesa. Intenta de nuevo.</p>";
                        }
                        // Cerrar cursor
                        $stmt_insert->closeCursor();
                    } else {
                        echo "<p class='text-danger'>El nombre asignado no es válido. Debe tener al menos 3 caracteres y contener solo letras.</p>";
                    }
                } elseif (isset($_POST['assigned_to']) && $_POST['assigned_to'] === '') {
                        echo "<p class='text-danger'>No has ingresado un nombre.</p>";
                }

                // Consulta para verificar si la mesa está asignada actualmente
                $stmt = $conn->prepare("
                    SELECT h.id_historial, h.fecha_A, h.assigned_by, h.assigned_to, c.name_camarero, c.surname_camarero
                    FROM tbl_historial h
                    JOIN tbl_camarero c ON h.assigned_by = c.id_camarero
                    WHERE h.id_mesa = ? AND h.fecha_NA IS NULL
                    ORDER BY h.fecha_A DESC
                    LIMIT 1
                ");
                $stmt->bindValue(1, $id_mesa, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                // Mostrar información de la asignación si existe
                if ($resultado) {
                    echo "<a href='mesas.php'><button class='btn btn-secondary back'>Volver a mesas</button></a>";
                    echo "<h2>Detalles de Asignación de la Mesa $id_mesa</h2>";
                    echo "<p><strong>Fecha de Asignación:</strong> " . htmlspecialchars($resultado['fecha_A']) . "</p>";
                    echo "<p><strong>Asignada por:</strong> " . htmlspecialchars($resultado['name_camarero']) . " " . htmlspecialchars($resultado['surname_camarero']) . "</p>";
                    echo "<p><strong>Asignada a:</strong> " . htmlspecialchars($resultado['assigned_to']) . "</p>";

                    // Botón de desasignar con IDs correctos
                    echo "<form method='POST' action='' id='form-desasignar'>";
                    echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                    echo "<input type='hidden' name='desasignar' value='true'>";
                    echo "<button type='submit' id='btn-desasignar' class='btn btn-rojo'>Desasignar</button>";
                    echo "</form>";
                } else {
                    echo "<a href='mesas.php'><button class='btn btn-secondary back'>Volver a mesas</button></a>";
                    echo "<p>Esta mesa no está asignada actualmente.</p>";

                    echo "<form method='POST' action='' id='form-asignar'>";
                    echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                    echo "<label for='assigned_to'>Asignar a: </label>";
                    echo "<input type='text' id='assigned_to' name='assigned_to' class='form-control mb-2'>";
                    echo "<button type='submit' id='btn-asignar' class='btn btn-verde'>Asignar</button>";
                    echo "</form>";
                }

                // Cerrar cursor
                $stmt->closeCursor();
            } else {
                echo "<p>No se ha seleccionado ninguna mesa.</p>";
            }

            // Cerrar conexión
            $conn = null;
        ?>
    </div>
    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Archivo de JavaScript con las alertas -->
<script src="../JS/alert.js"></script>
