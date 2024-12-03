<?php
    $server = "localhost";
    $user = "root";
    $pwd = "";
    $db = "db_restaurante";

    try {
        $conn = new mysqli($server, $user, $pwd, $db);

        // Verifica la conexión
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

    } catch (Exception $e) {
        // Manejo de errores
        die("Error: " . $e->getMessage());
    }