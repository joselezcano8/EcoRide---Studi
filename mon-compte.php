<?php
$pageTitle = 'Mon Compte';
$headerImg = 'img/woman_driving.jpg';
$titleColor = '--clr-clear';
$firstTitle = '';
$secondTitle = 'Mon espace utilisateur';
include 'inc/header.inc.php';


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

$userID = $_SESSION['user_id'];


$stmt = $conn->prepare('SELECT ID FROM chauffeur WHERE ID = ?');
$stmt->bind_param('i', $userID);
$stmt->execute();
$stmt->bind_result($ID);
$stmt->fetch();
$stmt->close();

$isChauffeur = isset($ID);


$marques_ecologiques = [
    "Tesla", "Rivian", "Lucid", "NIO", "Fisker", "Polestar", "XPeng", "BYTON",
    "Faraday Future", "Aptera", "Lordstown", "Canoo", "Bollinger", "Sono Motors", "Lightyear"
];

$modeles_ecologiques = [
    "Nissan Leaf", "Chevrolet Bolt", "BMW i3", "BMW i4", "BMW iX", "Hyundai Kona Electric",
    "Hyundai Ioniq Electric", "Hyundai Ioniq 5", "Kia Soul EV", "Kia Niro EV", "Kia EV6",
    "Audi e-tron", "Audi Q4 e-tron", "Audi e-tron GT", "Volkswagen ID.3", "Volkswagen ID.4",
    "Volkswagen ID. Buzz", "Porsche Taycan", "Jaguar I-Pace", "Mercedes-Benz EQC", "Mercedes-Benz EQS",
    "Mercedes-Benz EQA", "Mercedes-Benz EQB", "Ford Mustang Mach-E", "Ford F-150 Lightning",
    "Honda e", "Mazda MX-30", "Renault Zoe", "Renault Twingo Electric", "Renault Megane E-Tech",
    "Peugeot e-208", "Peugeot e-2008", "Opel Corsa-e", "Opel Mokka-e", "Mini Electric",
    "Volvo XC40 Recharge", "Volvo C40 Recharge"
];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_vehicule'])) {
    $plaque = ucfirst(strtolower(trim($_POST['plaque'])));
    $date_plaque = $_POST['date_plaque'];
    $marque = ucfirst(strtolower(trim($_POST['marque'])));
    $modele = ucfirst(strtolower(trim($_POST['modele'])));
    $couleur = ucfirst(strtolower(trim($_POST['couleur'])));
    $nombre_de_places = $_POST['nombre_de_places'];
    $fumeur = isset($_POST['fumeur']) ? 1 : 0;
    $animaux = isset($_POST['animaux']) ? 1 : 0;
    $preferences = ($_POST['preferences']);

    $eco = false;
    if (in_array($marque, $marques_ecologiques) || in_array($modele, $modeles_ecologiques)) {
        $eco = true;
    }

    $stmt = $conn->prepare('INSERT INTO vehicule (ID_chauffeur, plaque, date_plaque, marque, modele, couleur, nombre_de_places, fumeur, animaux, preferences, eco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssssiiisi', $ID, $plaque, $date_plaque, $marque, $modele, $couleur, $nombre_de_places, $fumeur, $animaux, $preferences, $eco);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_trajet'])) {
    $adresse_depart = ucfirst(strtolower(trim($_POST['adresse_depart'])));
    $adresse_arrivee = ucfirst(strtolower(trim($_POST['adresse_arrivee'])));
    $heure_depart = $_POST['heure_depart'];
    $heure_arrivee = $_POST['heure_arrivee'];
    $date = $_POST['date'];
    $prix = $_POST['prix'];
    $vehicule_id = $_POST['vehicule'];

    $stmt = $conn->prepare('SELECT nombre_de_places FROM vehicule WHERE ID_vehicule = ?');
    $stmt->bind_param('i', $vehicule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicule_data = $result->fetch_assoc();
    $places_disponibles = $vehicule_data['nombre_de_places'];
    $stmt->close();

    $stmtTrajet = $conn->prepare('INSERT INTO covoiturage (ID_vehicule, lieu_depart, lieu_arrivee, heure_depart, heure_arrivee, date, prix, statut, places_disponibles) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $statut = 'programmé';
    $stmtTrajet->bind_param('issssdisi', $vehicule_id, $adresse_depart, $adresse_arrivee, $heure_depart, $heure_arrivee, $date, $prix, $statut, $places_disponibles);


    if (!$stmtTrajet->execute()) {
        echo "Erreur lors de l'ajout du trajet : " . $stmtTrajet->error;
    }
    $stmtTrajet->close();
}

$reservations = [];
$stmt = $conn->prepare('SELECT * FROM participants WHERE ID_utilisateur = ?');
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
$stmt->close();


$trajets = [];
if ($isChauffeur) {
    $stmt = $conn->prepare('SELECT * FROM covoiturage c JOIN vehicule v ON c.ID_vehicule = v.ID_vehicule WHERE v.ID_chauffeur = ?');
    $stmt->bind_param('i', $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $trajets[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <style>
        input[type="text"]:not(:last-of-type), input[type="date"], select {
            text-transform: capitalize;
        }
    </style>
</head>
<body>
    <h1>Bienvenue sur votre compte</h1>

    <!-- Historique de Réservations -->
    <h2>Historique de Réservations</h2>

    <?php
    if (count($reservations) > 0): ?>
        <ul>
            <?php
            $counter = 1;
            foreach ($reservations as $reservation): ?>
                <li>Réservation <?php echo $counter++ ?> - <a href="detail-covoiturage.php?ID_covoiturage=<?php echo $reservation['ID_covoiturage']; ?>">Voir le covoiturage</a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Vous n'avez aucune réservation.</p>
    <?php endif; ?>

    <!-- Formulaire pour ajouter un véhicule si utilisateur est chauffeur -->
    <?php if ($isChauffeur): ?>
        <h2>Ajouter un Véhicule</h2>
        <form action="" method="POST">
            <label>Plaque d'immatriculation: <input type="text" name="plaque" required></label><br>
            <label>Date de mise en circulation: <input type="date" name="date_plaque" required></label><br>
            <label>Marque: <input type="text" name="marque" required></label><br>
            <label>Modèle: <input type="text" name="modele" required></label><br>
            <label>Couleur: <input type="text" name="couleur" required></label><br>
            <label>Nombre de places: <input type="number" name="nombre_de_places" min="1" required></label><br>
            <label>Fumeur: <input type="checkbox" name="fumeur"></label><br>
            <label>Animaux: <input type="checkbox" name="animaux"></label><br>
            <label>Préférences personnalisées: <input type="text" name="preferences"></label><br>
            <input type="hidden" name="ajouter_vehicule" value="1">
            <button type="submit">Ajouter Véhicule</button>
        </form>

        <!-- Liste des trajets du chauffeur -->
        <h2>Vos Trajets</h2>
        <?php if (count($trajets) > 0): ?>
            <ul>
                <?php
                $counter = 1;
                foreach ($trajets as $trajet): ?>
                    <li>Trajet <?php echo $counter++ ?> - <a href="detail-covoiturage.php?ID_covoiturage=<?php echo $trajet['ID_covoiturage']; ?>">Voir le covoiturage</a></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n'avez aucun trajet.</p>
        <?php endif; ?>

        <h2>Saisir un Nouveau Trajet</h2>
        <form action="" method="POST">
            <label>Adresse de départ: <input type="text" name="adresse_depart" list="depart-suggestions" required></label><br>
            <datalist id="depart-suggestions"></datalist>
            <label>Adresse d'arrivée: <input type="text" name="adresse_arrivee" list="arrive-suggestions" required></label><br>
            <datalist id="arrive-suggestions"></datalist>
            <label>Heure de départ: <input type="time" name="heure_depart" required></label><br>
            <label>Heure d'arrivée: <input type="time" name="heure_arrivee" required></label><br>
            <label>Date: <input type="date" name="date" required></label><br>
            <label>Prix: <input type="number" min="2" name="prix" step="0.5" required></label><br>
            <label>Véhicule: 
                <select name="vehicule">
                    <?php
                    $stmt = $conn->prepare('SELECT * FROM vehicule WHERE ID_chauffeur = ?');
                    $stmt->bind_param('i', $ID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($vehicule = $result->fetch_assoc()):
                    ?>
                        <option value="<?php echo $vehicule['ID_vehicule']; ?>"><?php echo $vehicule['marque'] . " " . $vehicule['modele']; ?></option>
                    <?php endwhile; ?>
                </select>
            </label><br>
            <input type="hidden" name="ajouter_trajet" value="1">
            <button type="submit">Ajouter Trajet</button>
        </form>
    <?php endif; ?>

    <!-- Bouton de déconnexion -->
    <form action="inc/logout.php" method="POST">
        <button type="submit">Déconnexion</button>
    </form>

    <?php include 'inc/footer.inc.php'; ?>
    <script src="script/script-itineraire.js"></script>
    <script src="script/modal.js"></script>
    <script src="script/reveal-sections.js"></script>
    <script src="script/connexion.js"></script>
</body>
</html>

<?php $conn->close(); ?>
