<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/estilos.css">
    <script src="./JS/validaciones.js"></script>
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="imgIndex">
            <img src="./CSS/img/logo/logo.png" alt="Imagen descriptiva">
        </div>

        <div class="botones">
            <button onclick="window.location.href='./formCamarero.php'">Iniciar sesión como Camarero</button>
            <button onclick="window.location.href='./formCliente.php'">Iniciar sesión como Cliente</button>
        </div>
    </div>
</body>
</html>