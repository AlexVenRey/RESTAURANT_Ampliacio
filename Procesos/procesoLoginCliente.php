<?php
    session_start();

    include_once("./conection.php");  // Usamos la conexión PDO establecida en "conection.php"

    if (!filter_has_var(INPUT_POST, 'enviar')) {
        header("Location: ../formCliente.php?error=inicioMal");
        exit();
    }

    $usr = htmlspecialchars($_POST["username"]);
    $pwd = hash('sha256', $_POST["pwd"]); // Se mantiene la misma forma de encriptación

    try {
        // Aquí ya no es necesario crear una nueva conexión, usamos la conexión que ya existe en $conn
        // En este punto ya tienes la conexión PDO establecida en el archivo "conection.php"

        $sqlInicio = "SELECT id_usuario, pwd_usuario FROM tbl_usuarios WHERE username_usuario = :username";
        $stmt = $conn->prepare($sqlInicio);  // Usamos $conn de "conection.php"
        $stmt->bindParam(':username', $usr, PDO::PARAM_STR);
        $stmt->execute();

        // Verificar si se encontró el usuario
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION["usuarioID"] = $row["id_usuario"];

            // Verificar si las contraseñas coinciden
            if ($pwd !== $row["pwd_usuario"]) {
                header("Location: ../formCliente.php?error=datosMal");
                exit();
            }

            // Redirigir a salas.php con el parámetro de éxito
            header("Location: ../Paginas/salas.php?login=success");
            exit();
        } else {
            header("Location: ../formCliente.php?error=datosMal");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error al iniciar sesión: " . $e->getMessage();
        die();
    }
?>
