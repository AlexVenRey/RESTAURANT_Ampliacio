<?php
    $server = "localhost";
    $user = "root";
    $pwd = "";
    $db = "db_restaurante";

    try {
        // Crear la conexión usando PDO
        $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pwd);
        
        // Establecer el modo de error para PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Manejo de errores en caso de que la conexión falle
        die("Error: " . $e->getMessage());
    }
?>
