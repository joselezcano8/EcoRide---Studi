<!-- Filtres de Recherche -->
<div class="filtres">
    <button class="button | btn-filtres">Filtres</button>
    <a href="covoiturages.php">effacer filtres</a>
</div>
<form action="covoiturages.php" method="GET" class="form-filtres | hidden">
    <div class="form-filtres-div">
        <div>
            <label for="eco">Voyage écologique</label>
            <input type="checkbox" name="eco" id="eco" default="" <?php echo isset($_GET['eco']) ? 'checked' : ''; ?>>
        </div>
            
        <div>
            <label for="prix">Prix maximum</label>
            <input type="number" name="prix" id="prix" min="1" step="0.1" value="<?php echo isset($_GET['prix']) ? $_GET['prix'] : ''; ?>">
        </div>
            
        <div>
            <label for="duree">Durée maximale (h)</label>
            <input type="number" name="duree" id="duree" min="1" value="<?php echo isset($_GET['duree']) ? $_GET['duree'] : ''; ?>">
        </div>
            
        <div>
            <label for="note">Note minimale du covoitureur</label>
            <div class="range">
                <input type="range" name="note" id="note" min="1" max="5" step="1" default="3" value="<?php echo isset($_GET['note']) ? $_GET['note'] : ''; ?>">
                <p><output id="value"></output></p>
            </div>
        </div>
    </div>

    <!-- Input de Itineraire -->
    <input type="hidden" name="depart" value="<?php echo isset($_GET['depart']) ? $_GET['depart'] : ''; ?>">
    <input type="hidden" name="arrive" value="<?php echo isset($_GET['arrive']) ? $_GET['arrive'] : ''; ?>">
    <input type="hidden" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
    <input type="hidden" name="passagers" value="<?php echo isset($_GET['passagers']) ? $_GET['passagers'] : 1; ?>">
    <input type="submit" value="Filtrer" class="button">
</form>
<div class="cards">
<?php


/* Connexion à la base de données */
require_once 'config.php';


// Récupérer les paramètres de recherche
$depart = isset($_GET['depart']) ? $_GET['depart'] : '';
$arrive = isset($_GET['arrive']) ? $_GET['arrive'] : '';
$passagers = isset($_GET['passagers']) ? intval($_GET['passagers']) : 1;
$date = isset($_GET['date']) ? $_GET['date'] : '';
if ($passagers < 1) {
    $passagers = 1;
}

$eco = isset($_GET['eco']) ? $_GET['eco'] : '';
$duree = isset($_GET['duree']) ? intval($_GET['duree']) : '';
$prix = isset($_GET['prix']) && is_numeric($_GET['prix']) ? (float)$_GET['prix'] : '';
$note = isset($_GET['note']) ? $_GET['note'] : 1;

$searchPerformed = false; // Variable pour vérifier si une recherche a été effectuée
if (!empty($depart) && !empty($arrive)) {
    $searchPerformed = true;
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

$params = []; // Tableau pour stocker les paramètres de la requête
$types  = ''; // Chaîne pour stocker les types des paramètres

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

if (!empty($date)) {
    $sql .= ' AND c.date >= ?';
    $params[] = $date;
    $types .= 's';
}

if (!empty($passagers)) {
    $sql .= ' AND c.places_disponibles >= ?';
    $params[] = $passagers;
    $types .= 'i';
}

if (!empty($eco)) {
    $sql .= ' AND vehicule.eco = 1';
}

if (!empty($prix)) {
    $sql .= ' AND c.prix <= ?';
    $params[] = $prix;
    $types .= 'd';
}

if (!empty($duree)) {
    $sql .= ' AND TIMESTAMPDIFF(HOUR, c.heure_depart, c.heure_arrivee) <= ?';
    $params[] = $duree;
    $types .= 'i';
}

if (!empty($note)) {
    $sql .= ' AND chauffeur.note >= ?';
    $params[] = $note;
    $types .= 'd'; 
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

// Vérifier si une recherche a été effectuée
if ($searchPerformed) {
    if ($result->num_rows > 0) {
        // Afficher les résultats si des covoiturages sont disponibles
        while ($row = $result->fetch_assoc()) {
        $chauffeurPseudo = htmlspecialchars($row['chauffeur_pseudo']);
        $chauffeurNote = htmlspecialchars($row['chauffeur_note']);
        $heureDepart = htmlspecialchars($row['heure_depart_formatee']);
        $heureArrivee = htmlspecialchars($row['heure_arrivee_formatee']);
        $date_formatee = htmlspecialchars($row['date_formatee']);
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
                    <p><?php echo $date_formatee?></p>
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
                    <form action="detail-covoiturage.php" method="GET">
                        <input type="hidden" name="ID_covoiturage" value="<?php echo $ID_covoiturage; ?>">
                        <button type="submit" class="button">Détail</button>
                    </form>
                </div>
            </div>
            
            <?php
        }
    } else {
        echo '</div><p>Aucun covoiturage disponible.</p>';
}}
else {
    echo '</div><p>Veuillez indiquer le lieu de départ, d\'arrivée, la date et le nombre de passagers...</p>';
}

// Fermer la déclaration
$stmt->close();
$conn->close();
?>
