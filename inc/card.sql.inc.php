<?php
// inc/card.sql.inc.php

// Afficher les erreurs (uniquement pour le développement ; à retirer en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ecoride';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connexion échouée : ' . $conn->connect_error);
}

// Récupérer les paramètres de recherche
$depart = isset($_POST['depart']) ? $_POST['depart'] : '';
$arrive = isset($_POST['arrive']) ? $_POST['arrive'] : '';
$passagers = isset($_POST['passagers']) ? intval($_POST['passagers']) : 1;
if ($passagers < 1) {
    $passagers = 1;
}

// Construire la requête SQL
$sql = '
SELECT
     c.ID_covoiturage,
     compte.pseudo AS chauffeur_pseudo,
     chauffeur.note AS chauffeur_note,
     DATE_FORMAT(c.heure_depart, "%Hh%i") AS heure_depart_formatee,
     DATE_FORMAT(c.heure_arrivee, "%Hh%i") AS heure_arrivee_formatee,
     DATE_FORMAT(c.date, "%d/%c/%Y") AS date_formatee,
     c.places_disponibles,
     vehicule.eco AS vehicule_eco,
     c.prix
FROM
    Covoiturage c
JOIN
    Vehicule vehicule ON c.ID_vehicule = vehicule.ID_vehicule
JOIN
    Chauffeur chauffeur ON vehicule.ID_chauffeur = chauffeur.ID
JOIN
    Compte compte ON chauffeur.ID = compte.ID
WHERE
    c.statut = "programme"
';

$params = [];
$types  = '';

// Ajouter des conditions en fonction des paramètres fournis
if (!empty($depart)) {
    $sql .= ' AND c.lieu_depart = ?';
    $params[] = $depart;
    $types .= 's';
}

if (!empty($arrive)) {
    $sql .= ' AND c.lieu_arrivee = ?';
    $params[] = $arrive;
    $types .= 's';
}

if (!empty($passagers)) {
    $sql .= ' AND c.places_disponibles >= ?';
    $params[] = $passagers;
    $types .= 'i';
}

$sql .= ' ORDER BY c.date, c.heure_depart';

// Préparer la déclaration
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Erreur lors de la préparation de la requête : ' . $conn->error);
}

// Lier les paramètres s'ils existent
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Exécuter la requête
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si c'est une requête AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $covoiturages = [];
    while ($row = $result->fetch_assoc()) {
        $covoiturages[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $covoiturages]);
    $stmt->close();
    $conn->close();
    exit;
}

// Générer les cartes si ce n'est pas une requête AJAX
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chauffeurPseudo = htmlspecialchars($row['chauffeur_pseudo']);
        $chauffeurNote = htmlspecialchars($row['chauffeur_note']);
        $heureDepart = htmlspecialchars($row['heure_depart_formatee']);
        $heureArrivee = htmlspecialchars($row['heure_arrivee_formatee']);
        $date = htmlspecialchars($row['date_formatee']);
        $placesDisponibles = htmlspecialchars($row['places_disponibles']);
        $vehiculeEco = $row['vehicule_eco'] ? 'Oui' : 'Non';
        $prix = htmlspecialchars($row['prix']);
        $ID_covoiturage = htmlspecialchars($row['ID_covoiturage']);
        ?>

        <div class="card">
            <div class="card-chauffeur">  
                <img src="img/svg/user.svg" alt="">
                <p><?php echo $chauffeurPseudo; ?></p>
            </div>
            <div class="card-star">
                <img src="img/svg/star.svg" alt="">
                <p><?php echo $chauffeurNote; ?>/5</p>
            </div>
            <div class="card-date">
                <p><?php echo $date?></p>
            </div>
            <div class="card-horaire">
                <p><?php echo $heureDepart; ?> - <?php echo $heureArrivee; ?></p>
            </div>
            <div class="card-car">
                <img src="img/svg/car.svg" alt="">
                <p><?php echo $placesDisponibles; ?> places</p>
            </div>
            <div class="card-eco">
                <img src="img/svg/feuille.svg" alt="">
                <p><?php echo $vehiculeEco; ?></p>
            </div>
            <div class="card-price">
                <h2><?php echo $prix; ?> Crédits</h2>
            </div>
            <div class="card-button">
                <form action="detail_covoiturage.php" method="get">
                    <input type="hidden" name="ID_covoiturage" value="<?php echo $ID_covoiturage; ?>">
                    <button type="submit" class="button">Détail</button>
                </form>
            </div>
        </div>

        <?php
    }
} else {
    echo '<p>Aucun covoiturage disponible.</p>';
}

$stmt->close();
$conn->close();
?>
