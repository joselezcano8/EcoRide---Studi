<?php
$pageTitle = 'Mon Compte';
$headerImg = 'img/woman_driving.jpg';
$titleColor = '--clr-clear';
$firstTitle = '';
$secondTitle = 'Mon espace utilisateur';

// Header
include 'inc/header.inc.php';

// Connexion à la base de données
include 'inc/config.php';

// Vérification si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

// Récupération de l'ID utilisateur depuis la session
$userID = $_SESSION['user_id'];

// Requête pour obtenir le pseudo de l'utilisateur connecté
$stmt = $conn->prepare('SELECT pseudo FROM compte WHERE ID = ?');
$stmt->bind_param('i', $userID);
$stmt->execute();
$pseudo = $stmt->get_result()->fetch_assoc()['pseudo'];
$stmt->close();

// Vérification si l'utilisateur est un chauffeur
$stmt = $conn->prepare('SELECT ID FROM chauffeur WHERE ID = ?');
$stmt->bind_param('i', $userID);
$stmt->execute();
$stmt->bind_result($ID);
$stmt->fetch();
$stmt->close();
$isChauffeur = isset($ID);

// Liste des marques et modèles de véhicules considérés comme écologiques
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

// Vérification de l'ajout d'un véhicule par le chauffeur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_vehicule'])) {
    // Traitement des champs du formulaire pour ajouter un véhicule
    $plaque = ucfirst(strtolower(trim($_POST['plaque'])));
    $date_plaque = $_POST['date_plaque'];
    $marque = ucfirst(strtolower(trim($_POST['marque'])));
    $modele = ucfirst(strtolower(trim($_POST['modele'])));
    $couleur = ucfirst(strtolower(trim($_POST['couleur'])));
    $nombre_de_places = $_POST['nombre_de_places'];
    $fumeur = isset($_POST['fumeur']) ? 1 : 0;
    $animaux = isset($_POST['animaux']) ? 1 : 0;
    $preferences = ($_POST['preferences']);

    // Vérification si le véhicule est écologique
    $eco = false;
    if (in_array($marque, $marques_ecologiques) || in_array($modele, $modeles_ecologiques)) {
        $eco = true;
    }

    // Insertion du véhicule dans la base de données
    $stmt = $conn->prepare('INSERT INTO vehicule (ID_chauffeur, plaque, date_plaque, marque, modele, couleur, nombre_de_places, fumeur, animaux, preferences, eco) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssssiiisi', $ID, $plaque, $date_plaque, $marque, $modele, $couleur, $nombre_de_places, $fumeur, $animaux, $preferences, $eco);
    $stmt->execute();
    $stmt->close();
}

// Vérification de l'ajout d'un trajet par le chauffeur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_trajet'])) {
    // Traitement des champs du formulaire pour ajouter un trajet
    $adresse_depart = ucfirst(strtolower(trim($_POST['adresse_depart'])));
    $adresse_arrivee = ucfirst(strtolower(trim($_POST['adresse_arrivee'])));
    $heure_depart = $_POST['heure_depart'];
    $heure_arrivee = $_POST['heure_arrivee'];
    $date = $_POST['date'];
    $prix = $_POST['prix'];
    $vehicule_id = $_POST['vehicule'];

    // Récupération du nombre de places disponibles pour le véhicule sélectionné
    $stmt = $conn->prepare('SELECT nombre_de_places FROM vehicule WHERE ID_vehicule = ?');
    $stmt->bind_param('i', $vehicule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicule_data = $result->fetch_assoc();
    $places_disponibles = $vehicule_data['nombre_de_places'];
    $stmt->close();

    // Insertion du trajet dans la base de données
    $stmtTrajet = $conn->prepare('INSERT INTO covoiturage (ID_vehicule, lieu_depart, lieu_arrivee, heure_depart, heure_arrivee, date, prix, statut, places_disponibles) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $statut = 'programmé';
    $stmtTrajet->bind_param('isssssisi', $vehicule_id, $adresse_depart, $adresse_arrivee, $heure_depart, $heure_arrivee, $date, $prix, $statut, $places_disponibles);


    if (!$stmtTrajet->execute()) {
        echo "Erreur lors de l'ajout du trajet : " . $stmtTrajet->error;
    }
    $stmtTrajet->close();
}

// Récupération de l'historique de réservations de l'utilisateur
$reservations = [];
$stmt = $conn->prepare('SELECT * FROM participants WHERE ID_utilisateur = ?');
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}
$stmt->close();

// Récupération des trajets du chauffeur si applicable
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

// Traitement du formulaire pour devenir chauffeur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['declarer_chauffeur'])) {
    $query = 'INSERT INTO chauffeur (ID) VALUES (?)';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
}
?>

    <main class="main | section">
        <h2>Bienvenue sur votre compte <?php echo $pseudo ?> </h2>

        <div class="reservations | padding">
            <!-- Historique de Réservations -->
            <h3>Historique de Réservations</h3>

            <?php
            if (count($reservations) > 0): ?>
                <ul>
                    <?php
                    $counter = 1;
                    foreach ($reservations as $reservation): ?>
                        <li><p>&bull; Réservation <?php echo $counter++ ?> - <a href="detail-covoiturage.php?ID_covoiturage=<?php echo $reservation['ID_covoiturage']; ?>">Voir le covoiturage</a></p></li>
                        <?php endforeach; ?>
                </ul>
                    <?php else: ?>
                <p>Vous n'avez aucune réservation.</p>
                <?php endif; ?>
        </div>
              
        
        <div class="ajouter-vehicule | padding">
            <!-- Formulaire pour ajouter un véhicule si utilisateur est chauffeur -->
            <?php if ($isChauffeur): ?>
                <h3>Ajouter un Véhicule</h3>
                <form  action="" method="POST">
                    <div class="ajouter-vehicule-form">
                        <div class="info-voiture-form">
                            <label>Plaque d'immatriculation: <input type="text" name="plaque" required></label>
                            <label>Date de mise en circulation: <input type="date" name="date_plaque" required></label>
                            <label>Marque: <input type="text" name="marque" required></label>
                            <label>Modèle: <input type="text" name="modele" required></label>
                            <label>Couleur: <input type="text" name="couleur" required></label>
                            <label>Nombre de places: <input type="number" name="nombre_de_places" min="1" required></label>
                        </div>
                        <div>
                            <h4>Préférences</h4>
                            <label>Fumeur: <input type="checkbox" name="fumeur"></label>
                            <label>Animaux: <input type="checkbox" name="animaux"></label>
                            <label>Préférences personnalisées: </label><textarea type="text" name="preferences"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="ajouter_vehicule" value="1">
                    <button type="submit" class="button">Ajouter Véhicule</button>
                </form>
        </div>

        <div class="trajets | padding">
            <!-- Liste des trajets du chauffeur -->
            <h3>Vos Trajets</h3>
            <?php if (count($trajets) > 0): ?>
                <ul>
                    <?php
                    $counter = 1;
                    foreach ($trajets as $trajet): ?>
                        <li><p>&bull; Trajet <?php echo $counter++ ?> - <a href="detail-covoiturage.php?ID_covoiturage=<?php echo $trajet['ID_covoiturage']; ?>">Voir le covoiturage</a></p></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Vous n'avez aucun trajet.</p>
            <?php endif; ?>
        </div>

        <!-- Saisir trajets -->
        <div class="saisir-trajets | padding">
            <h3>Saisir un Nouveau Trajet</h3>
            <form action="" method="POST">
                <div class="saisir-trajets-form">
                    <div>
                        <label>Adresse de départ: <input type="text" name="adresse_depart" list="depart-suggestions" required></label>
                        <datalist id="depart-suggestions"></datalist>
                        <label>Adresse d'arrivée: <input type="text" name="adresse_arrivee" list="arrive-suggestions" required></label>
                        <datalist id="arrive-suggestions"></datalist>
                        <label>Heure de départ: <input type="time" name="heure_depart" required></label>
                        <label>Heure d'arrivée: <input type="time" name="heure_arrivee" required></label>
                    </div>
                    <div>
                        <label>Date: <input type="date" name="date" required></label>
                        <label>Prix: <input type="number" min="2" name="prix" step="0.5" required></label>
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
                        </label>
                    </div>
                    </div>
                        <input type="hidden" name="ajouter_trajet" value="1">
                        <button type="submit" class="button">Ajouter Trajet</button>
            </form>
                    <?php else: ?>
                        <form method="POST" action="">
                            <button type="submit" name="declarer_chauffeur" class="button">Devenir Chauffeur</button>
                        </form>
                        <?php endif; ?>
                    </div>

        <!-- Bouton de déconnexion -->
        <div class="deconnexion">
            <form action="inc/logout.php" method="POST">
                <button type="submit" class="button">Déconnexion</button>
            </form>
        </div>
    
    </main>

    <!-- Footer -->
        <?php include 'inc/footer.inc.php'; ?>

        <!-- JavaScript -->
        <script src="script/script-itineraire.js"></script>
        <script src="script/modal.js"></script>
        <script src="script/reveal-sections.js"></script>
        <script src="script/connexion.js"></script>
    </body>
</html>

<?php $conn->close(); ?>
