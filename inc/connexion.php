<?php 
session_start();

// Connexion à la base de données
include 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données de connexion
    $connexion_utilisateur = $_POST['connexion-utilisateur'];
    $connexion_password = $_POST['connexion-password'];
    
    // Préparer la requête pour vérifier l'utilisateur
    $sqlLog = 'SELECT * FROM compte WHERE courriel = ? OR pseudo = ?';
    $stmtLog = $conn->prepare($sqlLog);
    $stmtLog->bind_param('ss', $connexion_utilisateur, $connexion_utilisateur);
    $stmtLog->execute();
    $resultLog = $stmtLog->get_result();
    
    // Vérifier si l'utilisateur existe
    if($resultLog->num_rows == 1) {
        $user = $resultLog->fetch_assoc();
        
        // Vérifier si le mot de passe est correct
        if ($connexion_password === $user['mot_de_passe']) {
            // Enregistrer les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_mail'] = $user['courriel'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['role'] = $user['role'];
            
            // Redirige en fonction du rôle de l'utilisateur
            if ($_SESSION['role'] === 'Employé') {
                echo json_encode(['success' => true, 'redirect' => 'private/compte-employe.php']);
            } else {
                echo json_encode(['success' => true, 'redirect' => '']);
            }
        } else {
            // Mot de passe incorrect
            echo json_encode(['success' => false, 'error' => 'Mot de passe incorrect.']);
        } 
    } else {
        // Utilisateur non trouvé
        echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé.']);
    }
}
// Fermer la connexion
$conn->close();
?>
