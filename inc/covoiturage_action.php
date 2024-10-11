<?php 
session_start();

// Connexion à la base de données
include 'config.php';

// Vérifier si l'utilisateur est connecté, sinon le rediriger vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupérer les données du formulaire et l'ID de l'utilisateur connecté
$action = $_POST['action'] ?? null;
$ID_covoiturage = $_POST['ID_covoiturage'] ?? null;
$userID = $_SESSION['user_id'];

// Récupérer le nombre de places disponibles pour le covoiturage
$stmt = $conn->prepare('SELECT places_disponibles FROM covoiturage WHERE ID_covoiturage = ?');
$stmt->bind_param('i', $ID_covoiturage);
$stmt->execute();
$stmt->bind_result($places_disponibles);
$stmt->fetch();
$stmt->close();

// Vérifier l'action choisie et effectuer l'opération correspondante
if($action && $ID_covoiturage) {
    switch($action) {
        case 'participer': // Participer au covoiturage
            if ($places_disponibles > 0) {
                // Décrémenter le nombre de places disponibles
                $new_places_disponibles = $places_disponibles - 1;
                $stmt = $conn->prepare('UPDATE covoiturage SET places_disponibles = ? WHERE ID_covoiturage = ?');
                $stmt->bind_param('ii', $new_places_disponibles, $ID_covoiturage);
                $stmt->execute();
                $stmt->close();
                
                // Ajouter l'utilisateur comme participant
                $stmt = $conn->prepare('INSERT INTO participants (ID_utilisateur, ID_covoiturage) VALUES (?, ?)');
                $stmt->bind_param('ii', $userID, $ID_covoiturage);
                $stmt->execute();
                $stmt->close();
            }
            break;

        case 'annuler_participation': // Annuler la participation
            // Incrémenter le nombre de places disponibles
            $new_places_disponibles = $places_disponibles + 1;
            $stmt = $conn->prepare('UPDATE covoiturage SET places_disponibles = ? WHERE ID_covoiturage = ?');
            $stmt->bind_param('ii', $new_places_disponibles, $ID_covoiturage);
            $stmt->execute();
            $stmt->close();

            // Supprimer l'utilisateur des participants
            $stmt = $conn->prepare("DELETE FROM participants WHERE ID_utilisateur = ? AND ID_covoiturage = ?");
            $stmt->bind_param('ii', $userID, $ID_covoiturage);
            $stmt->execute();
            break;

        case 'commencer': // Marquer le covoiturage comme "commencé"
            $stmt = $conn->prepare("UPDATE covoiturage SET statut = 'commencé' WHERE ID_covoiturage = ?");
            $stmt->bind_param('i', $ID_covoiturage);
            $stmt->execute();
            break;
    
        case 'terminer': // Marquer le covoiturage comme "terminé"
            $stmt = $conn->prepare("UPDATE covoiturage SET statut = 'terminé' WHERE ID_covoiturage = ?");
            $stmt->bind_param('i', $ID_covoiturage);
            $stmt->execute();
            break;
    
        case 'annuler': // Annuler le covoiturage
            $stmt = $conn->prepare("UPDATE covoiturage SET statut = 'annulé' WHERE ID_covoiturage = ?");
            $stmt->bind_param('i', $ID_covoiturage);
            $stmt->execute();
            break;
    }
    // Rediriger vers la page de détail du covoiturage
    header('Location: ../detail-covoiturage.php?ID_covoiturage=' . $ID_covoiturage);
    exit;
}
?>
