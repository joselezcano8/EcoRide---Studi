<?php 

session_start();
include 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['donner_avis'])) {

    $note = isset($_POST['nete']) ? (int)$_POST['note'] : 3;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $chauffeurID = isset($_POST['chauffeur_id']) ? (int)$_POST['chauffeur_id'] : 0;
    $userID = isset($_POST['utilisateur_id']) ? (int)$_POST['utilisateur_id'] : 0;
    $covoiturageID = isset($_POST['covoiturage_id']) ? (int)$_POST['covoiturage_id'] : 0;

    if($description !== '') {

        $stmt = $conn->prepare('INSERT INTO avis (ID_passager, ID_chauffeur, etoiles, description, statut) VALUES (?, ?, ?, ?, "en attente")');
        $stmt->bind_param('iiis', $userID, $chauffeurID, $note, $description);

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

header('Location: ../detail-covoiturage.php?ID_covoiturage=' . $covoiturageID);
exit();

$conn->close();



?>