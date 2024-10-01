<?php 
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecoride";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connexion échouée: ' . $conn->connect_error]);
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $connexion_utilisateur = $_POST['connexion-utilisateur'];
    $connexion_password = $_POST['connexion-password'];
    
    $sqlLog = 'SELECT * FROM compte WHERE courriel = ? OR pseudo = ?';
    $stmtLog = $conn->prepare($sqlLog);
    $stmtLog->bind_param('ss', $connexion_utilisateur, $connexion_utilisateur);
    $stmtLog->execute();
    $resultLog = $stmtLog->get_result();
    
    if($resultLog->num_rows == 1) {
        $user = $resultLog->fetch_assoc();
        
        if ($connexion_password === $user['mot_de_passe']) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_mail'] = $user['courriel'];
            $_SESSION['pseudo'] = $user['pseudo'];
            
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Mot de passe incorrect.']);
        } }
        else {
            echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé.']);
        }
    }
    $conn->close();
?>