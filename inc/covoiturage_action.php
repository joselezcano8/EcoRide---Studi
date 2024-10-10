<?php 
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecoride";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : null;
$ID_covoiturage = isset($_POST['ID_covoiturage']) ? $_POST['ID_covoiturage'] : null;
$userID = $_SESSION['user_id'];

$stmt = $conn->prepare('SELECT places_disponibles FROM covoiturage WHERE ID_covoiturage = ?');
$stmt->bind_param('i', $ID_covoiturage);
$stmt->execute();
$stmt->bind_result($places_disponibles);
$stmt->fetch();
$stmt->close();

if($action && $ID_covoiturage) {
    switch($action) {
        case 'participer':
            if ($places_disponibles > 0) {
                $new_places_disponibles = $places_disponibles - 1;
                
                $stmt = $conn->prepare('UPDATE covoiturage SET places_disponibles = ? WHERE ID_covoiturage = ?');
                $stmt->bind_param('ii', $new_places_disponibles, $ID_covoiturage);
                $stmt->execute();
                $stmt->close();
            
                $stmt = $conn->prepare('INSERT INTO participants (ID_utilisateur, ID_covoiturage) VALUES (?, ?)');
                $stmt->bind_param('ii', $userID, $ID_covoiturage);
                $stmt->execute();
                $stmt->close();
            }
            break;
        case 'annuler_participation':
            $new_places_disponibles = $places_disponibles + 1;
                
            $stmt = $conn->prepare('UPDATE covoiturage SET places_disponibles = ? WHERE ID_covoiturage = ?');
            $stmt->bind_param('ii', $new_places_disponibles, $ID_covoiturage);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM participants WHERE ID_utilisateur = ? AND ID_covoiturage = ?");
            $stmt->bind_param('ii', $userID, $ID_covoiturage);
            $stmt->execute();
            break;

        case 'commencer':
            $stmt = $conn->prepare("UPDATE covoiturage SET statut = 'commencé' WHERE ID_covoiturage = ?");
            $stmt->bind_param('i', $ID_covoiturage);
            $stmt->execute();
            break;
    
        case 'terminer':
            $stmt = $conn->prepare("UPDATE covoiturage SET statut = 'terminé' WHERE ID_covoiturage = ?");
            $stmt->bind_param('i', $ID_covoiturage);
            $stmt->execute();
            break;
    
        case 'annuler':
            $stmt = $conn->prepare("UPDATE covoiturage SET statut = 'annulé' WHERE ID_covoiturage = ?");
            $stmt->bind_param('i', $ID_covoiturage);
            $stmt->execute();
            break;
    }
    header('Location: ../detail-covoiturage.php?ID_covoiturage=' . $ID_covoiturage);
    exit;
}