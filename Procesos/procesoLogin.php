<?php
    session_start();

    include_once("./conection.php");

    if (!filter_has_var(INPUT_POST, 'enviar')) {
        header("Location: ../index.php?error=inicioMal");
        exit();
    }

    $usr = mysqli_escape_string($conn, htmlspecialchars($_POST["username"]));
    $pwd = mysqli_escape_string($conn, htmlspecialchars(hash('sha256', $_POST["pwd"])));

    try {
        $sqlInicio = "SELECT id_camarero, pwd_camarero FROM tbl_camarero WHERE username_camarero = ?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $sqlInicio);
        mysqli_stmt_bind_param($stmt, "s", $usr);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            $row = mysqli_fetch_assoc($resultado);
            $_SESSION["camareroID"] = $row["id_camarero"];

            if ($pwd !== $row["pwd_camarero"]) {
                header("Location: ../index.php?error=datosMal");
                exit();
            }

            // Redirige a index.php con el parámetro de éxito
            header("Location: ../Paginas/salas.php?login=success");
            exit();
        } else {
            header("Location: ../index.php?error=datosMal");
            exit();
        }
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo "Error al iniciar sesión: " . $e->getMessage();
        die();
    }