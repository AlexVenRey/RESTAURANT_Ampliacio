<?php
session_start();

include_once("./conection.php");

if (!filter_has_var(INPUT_POST, 'enviar')) {
    header("Location: ../index.php?error=inicioMal");
    exit();
}

// Usando PDO para preparar los datos
$usr = htmlspecialchars($_POST["username"]);
$pwd = hash('sha256', $_POST["pwd"]);

try {
    // Usamos PDO para hacer la consulta
    $sqlInicio = "SELECT id_camarero, username_camarero, pwd_camarero, roles FROM tbl_camarero WHERE username_camarero = :usr";
    $stmt = $conn->prepare($sqlInicio);
    $stmt->bindParam(':usr', $usr, PDO::PARAM_STR);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        // Verificamos el usuario y la contraseña
        if ($usr !== $resultado["username_camarero"]) {
            header("Location: ../index.php?error=datosMal");
            exit();
        }

        if ($pwd !== $resultado["pwd_camarero"]) {
            header("Location: ../index.php?error=datosMal");
            exit();
        }

        // Pasamos estos 2 datos del camarero para poder identificarlo en la siguientes paginas
        $_SESSION["camareroID"] = $resultado["id_camarero"];
        $_SESSION["rol"] = $resultado["roles"];

        // Redirige a salas.php con el parámetro de éxito
        header("Location: ../Paginas/salas.php?login=success");
        exit();
    } else {
        header("Location: ../index.php?error=datosMal");
        exit();
    }

} catch (Exception $e) {
    echo "Error al iniciar sesión: " . $e->getMessage();
    die();
}
?>
