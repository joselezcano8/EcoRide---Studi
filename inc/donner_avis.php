<?php 
session_start();
// Connexion à la base de données
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['donner_avis'])) {
    // Récupérer les données du formulaire
    $note = isset($_POST['note']) ? (int)$_POST['note'] : 3;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $chauffeurID = isset($_POST['chauffeur_id']) ? (int)$_POST['chauffeur_id'] : 0;
    $userID = isset($_POST['utilisateur_id']) ? (int)$_POST['utilisateur_id'] : 0;
    $covoiturageID = isset($_POST['covoiturage_id']) ? (int)$_POST['covoiturage_id'] : 0;

    // Vérifier si la description n'est pas vide
    if ($description !== '') {
        // Préparer la requête pour insérer l'avis dans la base de données
        $stmt = $conn->prepare('INSERT INTO avis (ID_passager, ID_chauffeur, ID_covoiturage, etoiles, description, statut) VALUES (?, ?, ?, ?, ?, "en attente")');
        
        if ($stmt) {
            $stmt->bind_param('iiiis', $userID, $chauffeurID, $covoiturageID, $note, $description);

            // Exécuter la requête et vérifier si elle a réussi
            if ($stmt->execute()) {
                $stmt->close();
                
                // Rediriger vers la page de détail du covoiturage
                 header('Location: ../detail-covoiturage.php?ID_covoiturage=' . $covoiturageID);
                exit();
            } else {
                echo 'Erreur lors de l\'envoi de l\'avis : ' . $stmt->error;
            }
        } else {
            echo 'Erreur de préparation de la requête : ' . $conn->error;
        }
    }
}

$conn->close();
?>