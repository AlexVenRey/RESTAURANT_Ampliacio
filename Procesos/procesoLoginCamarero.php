<?php
    session_start();

    include_once("./conection.php");  // Conexión PDO establecida aquí

    if (!filter_has_var(INPUT_POST, 'enviar')) {
        header("Location: ../formCamarero.php?error=inicioMal");
        exit();
    }

    $usr = htmlspecialchars($_POST["username"]);
    $pwd = hash('sha256', $_POST["pwd"]);

    try {
        if ($usr === 'admin' && $pwd === hash('sha256', 'admin')) {
            $sql_admin = "SELECT id_administrador FROM tbl_administrador WHERE username_administrador = :username AND pwd_administrador = :password";
            $stmt_admin = $conn->prepare($sql_admin);
            $stmt_admin->bindParam(':username', $usr, PDO::PARAM_STR);
            $stmt_admin->bindParam(':password', $pwd, PDO::PARAM_STR); // $pwd ya tiene el hash
            $stmt_admin->execute();
        
            if ($stmt_admin->rowCount() > 0) {
                // Redirigir a salas.php si el usuario administrador existe
                $row = $stmt_admin->fetch(PDO::FETCH_ASSOC);
                $_SESSION["adminID"] = $row["id_administrador"];
                header("Location: ../Paginas/salas.php");
                exit();
            } else {
                // Redirigir con error si no se encuentra el administrador
                header("Location: ../formCamarero.php?error=adminNoEncontrado");
                exit();
            }
        }
                        
        
        // Aquí ya no es necesario crear una nueva conexión, usamos la conexión que ya existe en $conn
        // En este punto ya tienes la conexión PDO establecida en el archivo "conection.php"

        $sql = "SELECT id_camarero, pwd_camarero FROM tbl_camarero WHERE username_camarero = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $usr, PDO::PARAM_STR);
        $stmt->execute();

        // Verificar si se encontró el camarero
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION["camareroID"] = $row["id_camarero"];

            // Verificar si las contraseñas coinciden
            if ($pwd !== $row["pwd_camarero"]) {
                header("Location: ../formCamarero.php?error=datosMal");
                exit();
            }

            // Redirigir a salas.php con el parámetro de éxito
            header("Location: ../Paginas/salas.php?login=success");
            exit();
        } else {
            header("Location: ../formCamarero.php?error=datosMal");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error al iniciar sesión: " . $e->getMessage();
        die();
    }
?>
