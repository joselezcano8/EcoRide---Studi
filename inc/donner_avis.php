<?php 

session_start();
// Connexion à la base de données
include 'config.php';

// Vérifier si le formulaire a été soumis et si l'utilisateur souhaite donner un avis
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['donner_avis'])) {

    // Récupérer les données du formulaire
    $note = isset($_POST['nete']) ? (int)$_POST['note'] : 3;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $chauffeurID = isset($_POST['chauffeur_id']) ? (int)$_POST['chauffeur_id'] : 0;
    $userID = isset($_POST['utilisateur_id']) ? (int)$_POST['utilisateur_id'] : 0;
    $covoiturageID = isset($_POST['covoiturage_id']) ? (int)$_POST['covoiturage_id'] : 0;

    // Vérifier si la description n'est pas vide
    if($description !== '') {

        // Préparer la requête pour insérer l'avis dans la base de données
        $stmt = $conn->prepare('INSERT INTO avis (ID_passager, ID_chauffeur, etoiles, description, statut) VALUES (?, ?, ?, ?, "en attente")');
        $stmt->bind_param('iiis', $userID, $chauffeurID, $note, $description);

        // Exécuter la requête et vérifier si elle a réussi
        if ($stmt->execute()) {
            echo 'Avis envoyé avec succès !';
        } else {
            echo 'Erreur lors de l\'envoi de l\'avis : ' . $stmt->error;
        }
        $stmt->close();
    } else {
        echo 'La description ne peut pas être vide.';
    }
}

// Rediriger vers la page de détail du covoiturage
header('Location: ../detail-covoiturage.php?ID_covoiturage=' . $covoiturageID);
exit();

$conn->close();
?>