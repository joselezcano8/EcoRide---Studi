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

        // Récupérer le prix du covoiturage
        $stmt = $conn->prepare('SELECT prix FROM covoiturage WHERE ID_covoiturage = ?');
        $stmt->bind_param('i', $ID_covoiturage);
        $stmt->execute();
        $stmt->bind_result($prixCovoiturage);
        $stmt->fetch();
        $stmt->close();

        // Récupérer les crédits de l'utilisateur
        $stmt = $conn->prepare('SELECT credits FROM utilisateur WHERE ID = ?');
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->bind_result($userCredits);
        $stmt->fetch();
        $stmt->close();

        // Vérifier si l'utilisateur a suffisamment de crédits pour participer
        if ($userCredits >= $prixCovoiturage) {
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
            } else {
                // Rediriger avec un message d'erreur si les crédits sont insuffisants
                header('Location: ../detail-covoiturage.php?ID_covoiturage=' . $ID_covoiturage . '&error=insufficient_credits');
                exit;
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
            if ($stmt->execute()) {
                $sql = "SELECT c.prix, v.ID_chauffeur FROM covoiturage c JOIN vehicule v ON c.ID_vehicule = v.ID_vehicule WHERE c.ID_covoiturage = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $ID_covoiturage);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $covoiturage = $result->fetch_assoc();
                    $chauffeurID = $covoiturage['ID_chauffeur'];
                    $prixCovoiturage = $covoiturage['prix'];

                    $sql = "SELECT ID_utilisateur FROM participants WHERE ID_covoiturage = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $ID_covoiturage);
                    $stmt->execute();
                    $participants = $stmt->get_result();

                    $totalParticipants = $participants->num_rows;
                    $reductionParticipant = $prixCovoiturage;
                    $creditChauffeur = ($prixCovoiturage - 2) * $totalParticipants;

                    while ($participant = $participants->fetch_assoc()) {
                        $participantID = $participant['ID_utilisateur'];
                        $stmtUpdate = $conn->prepare("UPDATE utilisateur SET credits = credits - ? WHERE ID = ?");
                        $stmtUpdate->bind_param('di', $reductionParticipant, $participantID);
                        $stmtUpdate->execute();
                    }

                    $stmtUpdate = $conn->prepare("UPDATE utilisateur SET credits = credits + ? WHERE ID = ?");
                    $stmtUpdate->bind_param('di', $creditChauffeur, $chauffeurID);
                    $stmtUpdate->execute();
                }
            }
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
