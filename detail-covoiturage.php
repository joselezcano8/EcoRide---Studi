<?php

$pageTitle = 'Détail du Covoiturage';
$headerImg = 'img/highway.jpg';
$titleColor = '--clr-dark';
$firstTitle = '';
$secondTitle = 'Détail du Covoiturage';
include 'inc/header.inc.php';

// Connexion à la base de données
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ecoride';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connexion échouée : ' . $conn->connect_error);
}

//Récuperer l'ID Covoiturage
if (isset($_GET['ID_covoiturage']) && is_numeric($_GET['ID_covoiturage'])) {
    $ID_covoiturage = intval($_GET['ID_covoiturage']);
}
else {
    echo('<p>ID covoiturage invalide</p>');
}

$sql = '
SELECT
   c.ID_covoiturage,
   c.ID_vehicule,
   c.date,
   DATE_FORMAT(c.heure_depart, "%Hh%i") AS heure_depart_formatee,
   DATE_FORMAT(c.heure_arrivee, "%Hh%i") AS heure_arrivee_formatee,
   c.prix,
   c.places_disponibles,
   vehicule.marque,
   vehicule.modele,
   vehicule.couleur,
   vehicule.fumeur,
   vehicule.animaux,
   vehicule.preferences,
   vehicule.plaque,
   vehicule.eco,
   compte.pseudo,
   compte.ID AS compte_ID
FROM
    Covoiturage c
JOIN
    Vehicule vehicule ON c.ID_vehicule = vehicule.ID_vehicule
JOIN
    Chauffeur chauffeur ON vehicule.ID_chauffeur = chauffeur.ID
JOIN
    Compte compte ON chauffeur.ID = compte.ID
WHERE
    c.ID_covoiturage = ?';

$stmt = $conn->prepare($sql);
if($stmt === false) {
    die('Erreur lors de la préparation de la requête : ' . $conn->error);
}

$stmt->bind_param('i', $ID_covoiturage);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    $covoiturage = $result->fetch_assoc();
} else echo('<p>Covoiturage non trouvé. <a href="covoiturages.php">Retour à la liste des covoiturages</a></p>');

$covoiturage = isset($covoiturage) ? $covoiturage : null;
if ($covoiturage) {
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    $timestamp = strtotime($covoiturage['date']);
    
    // Formater la date avec strftime()
    $date_formatee = strftime('%d %B %Y', $timestamp);
    // Vérifier si la date est valide
    if (!empty($covoiturage['date'])) {
    // Convertir la date en timestamp
        $timestamp = strtotime($covoiturage['date']);
    // Formater la date
        $date_formatee = strftime('%d %B %Y', $timestamp);
    } else {
        $date_formatee = '';
    }
} else;


$stmt->close();
$conn->close();

$ID_covoiturage = isset($ID_covoiturage) ? $ID_covoiturage : null;
if($ID_covoiturage && $covoiturage): ?>
<main class="main">
    <section class="info-covoiturage | padding | section">
        <div class="infos">
            <h3>Informations du Covoiturage</h3>
            <p>Pseudo du chauffeur: <?php echo htmlspecialchars($covoiturage['pseudo']) ?></p>
            <p>Nombre de places restantes: <?php echo htmlspecialchars($covoiturage['places_disponibles']) ?> places restantes</p>
            <p>Prix: <?php echo htmlspecialchars($covoiturage['prix']) ?> crédits</p>
            <p>Date et Heure de Départ: <?php echo htmlspecialchars($date_formatee); ?>, <?php echo htmlspecialchars($covoiturage['heure_depart_formatee']); ?></p>
            <p>Date et Heure d'Arrivée: <?php echo htmlspecialchars($date_formatee); ?>, <?php echo htmlspecialchars($covoiturage['heure_arrivee_formatee']); ?></p>
            <p>Voyage ecologique: <?php echo $covoiturage['eco'] ? 'Oui' : 'Non'; ?></p>
        </div>
        <div class="details-vehicule">
            <h3>Détails du Véhicule</h3>
            <p>Marque: <?php echo htmlspecialchars($covoiturage['marque']) ?></p>
            <p>Modéle: <?php echo htmlspecialchars($covoiturage['modele']) ?></p>
            <p>Couleur: <?php echo htmlspecialchars($covoiturage['couleur']) ?></p>
            <p>Plaque: <?php echo htmlspecialchars($covoiturage['plaque']) ?></p>
        </div>
        <div class="preferences">
            <h3>Préferénces du Conducteur</h3>
            <p>Animaux: <?php echo $covoiturage['animaux'] ? 'Acceptés' : 'Non'; ?></p>
            <p>Fumeurs: <?php echo $covoiturage['fumeur'] ? 'Acceptés' : 'Non'; ?></p>
            <p>Autres préférences: <?php echo htmlspecialchars($covoiturage['preferences']) ?></p>
        </div>
    </section>
</main>
<?php endif; ?>


</body>
<?php include 'inc/footer.inc.php'; ?>
<script src="script/modal.js"></script>
<script src="script/reveal-sections.js"></script>
</body>
</html>

