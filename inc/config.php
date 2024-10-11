<?php

/* Connexion  à la base de données */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecoride";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
?>
