<?php
session_start();
include_once("./conection.php");

if (!filter_has_var(INPUT_POST, 'enviar')) {
    header("Location: ../index.php?error=inicioMal");
    exit();
}

$usr = htmlspecialchars($_POST["username"]);
$pwd = hash('sha256', $_POST["pwd"]);

try {
    $sqlInicio = "SELECT id_camarero, username_camarero, pwd_camarero, roles FROM tbl_camarero WHERE username_camarero = :usr";
    $stmt = $conn->prepare($sqlInicio);
    $stmt->bindParam(':usr', $usr, PDO::PARAM_STR);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        if ($usr !== $resultado["username_camarero"] || $pwd !== $resultado["pwd_camarero"]) {
            header("Location: ../index.php?error=datosMal");
            exit();
        }

        $_SESSION["camareroID"] = $resultado["id_camarero"];
        $_SESSION["rol"] = $resultado["roles"];

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
