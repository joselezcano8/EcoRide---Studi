<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecoride";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>
