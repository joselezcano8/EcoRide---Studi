<?php
$pageTitle = 'Détail du Covoiturage';
$headerImg = 'img/highway.jpg';
$titleColor = '--clr-dark';
$firstTitle = '';
$secondTitle = 'Détail du Covoiturage';
include 'inc/header.inc.php';
require_once 'inc/config.php';

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
   c.lieu_depart,
   c.lieu_arrivee,
   c.prix,
   c.places_disponibles,
   c.statut,
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
} else echo('<div style="height: 40%"><p style="text-align: center">Covoiturage non trouvé. <a href="covoiturages.php">Retour à la liste des covoiturages</a></p></div>');

$covoiturage = isset($covoiturage) ? $covoiturage : null;
if ($covoiturage && !empty($covoiturage['date'])) {
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    $dateObj = DateTime::createFromFormat('Y-m-d', $covoiturage['date']);
    $formatter = new IntlDateFormatter(
        'fr_FR', 
        IntlDateFormatter::LONG, 
        IntlDateFormatter::NONE, 
        'Europe/Paris', 
        IntlDateFormatter::GREGORIAN, 
        'd MMMM yyyy'
    );
    $date_formatee = $formatter->format($dateObj);
} else {
    $date_formatee = '';
}



$ID_covoiturage = isset($ID_covoiturage) ? $ID_covoiturage : null;
if($ID_covoiturage && $covoiturage): ?>
<main class="main">
    <section class="info-covoiturage | padding | section">
        <div class="infos">
            <h3>Informations du Covoiturage</h3>
            <p><strong>&bullet; Pseudo du chauffeur:</strong> <?php echo htmlspecialchars($covoiturage['pseudo']) ?></p>
            <p><strong>&bullet; Lieu de Départ:</strong> <?php echo htmlspecialchars($covoiturage['lieu_depart']) ?></p>
            <p><strong>&bullet; Lieu d'Arrivée:</strong> <?php echo htmlspecialchars($covoiturage['lieu_arrivee']) ?></p>
            <p><strong>&bullet; Nombre de places restantes:</strong> <?php echo htmlspecialchars($covoiturage['places_disponibles']) ?> places restantes</p>
            <p><strong>&bullet; Prix:</strong> <?php echo htmlspecialchars($covoiturage['prix']) ?> crédits</p>
            <p><strong>&bullet; Date et Heure de Départ:</strong> <?php echo htmlspecialchars($date_formatee); ?>, <?php echo htmlspecialchars($covoiturage['heure_depart_formatee']); ?></p>
            <p><strong>&bullet; Date et Heure d'Arrivée:</strong> <?php echo htmlspecialchars($date_formatee); ?>, <?php echo htmlspecialchars($covoiturage['heure_arrivee_formatee']); ?></p>
            <p><strong>&bullet; Voyage ecologique:</strong> <?php echo $covoiturage['eco'] ? 'Oui' : 'Non'; ?></p>
            <p><strong>&bullet; Statut:</strong> <?php echo ucfirst(htmlspecialchars($covoiturage['statut'])) ?></p>
        </div>
        <div class="details-vehicule">
            <h3>Détails du Véhicule</h3>
            <p><strong>&bullet; Marque:</strong> <?php echo htmlspecialchars($covoiturage['marque']) ?></p>
            <p><strong>&bullet; Modéle:</strong> <?php echo htmlspecialchars($covoiturage['modele']) ?></p>
            <p><strong>&bullet; Couleur:</strong> <?php echo htmlspecialchars($covoiturage['couleur']) ?></p>
            <p><strong>&bullet; Plaque:</strong> <?php echo strtoupper(htmlspecialchars($covoiturage['plaque'])) ?></p>
        </div>
        <div class="preferences">
            <h3>Préferénces du Conducteur</h3>
            <p><strong>&bullet; Animaux:</strong> <?php echo $covoiturage['animaux'] ? 'Acceptés' : 'Non'; ?></p>
            <p><strong>&bullet; Fumeurs:</strong> <?php echo $covoiturage['fumeur'] ? 'Acceptés' : 'Non'; ?></p>
            <p><strong>&bullet; Autres préférences:</strong> <?php echo htmlspecialchars($covoiturage['preferences']) ?></p>
        </div>
    </section>
    
    <?php 
    $utilisateur_connecte = isset($_SESSION['user_id']) ? true : false;

    if ($utilisateur_connecte) : {
            $userID = $_SESSION['user_id'];
            $chauffeurID = $covoiturage['compte_ID'];

            $query = $conn->prepare('SELECT * FROM participants WHERE ID_covoiturage = ? AND ID_utilisateur = ?');
            $query->bind_param('ii', $ID_covoiturage, $userID);
            $query->execute();
            $result = $query->get_result();
            $isParticipant = $result->fetch_assoc();

            $isChauffeur = ($userID == $chauffeurID);

            $stmtAvis = $conn->prepare('SELECT COUNT(*) FROM avis WHERE ID_passager = ? AND ID_chauffeur = ?');
            $stmtAvis->bind_param('ii', $userID, $chauffeurID);
            $stmtAvis->execute();
            $stmtAvis->bind_result($count);
            $stmtAvis->fetch();
            $stmtAvis->close();

            if ($isChauffeur) : ?>
                <div class="chauffeur-btns" style="justify-self: center;">
                    <?php if ($covoiturage['statut'] == 'programmé'): ?>
                        <form action="inc/covoiturage_action.php" method="POST">
                            <input type="hidden" name="action" value="commencer">
                            <input type="hidden" name="ID_covoiturage" value="<?php echo $ID_covoiturage; ?>">
                            <button class="button">Commencer le covoiturage</button>
                        </form>
                        <form action="inc/covoiturage_action.php" method="POST">
                            <input type="hidden" name="action" value="annuler">
                            <input type="hidden" name="ID_covoiturage" value="<?php echo $ID_covoiturage; ?>">
                            <button class="button">Annuler le covoiturage</button>
                        </form>
                    <?php elseif ($covoiturage['statut'] == 'commencé'): ?>
                        <form action="inc/covoiturage_action.php" method="POST">
                            <input type="hidden" name="action" value="terminer">
                            <input type="hidden" name="ID_covoiturage" value="<?php echo $ID_covoiturage; ?>">
                            <button class="button">Terminer le covoiturage</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php elseif ($isParticipant):
                if ($covoiturage['statut'] == 'programmé'): ?>
                    <form action="inc/covoiturage_action.php" method="POST" style="justify-self: center;">
                        <input type="hidden" name="action" value="annuler_participation">
                        <input type="hidden" name="ID_covoiturage" value="<?php echo $ID_covoiturage; ?>">
                        <button class="button">Annuler ma participation</button>
                    </form>
                <?php
                 elseif ($covoiturage['statut'] == 'terminé' && $count == 0): ?>
                    <button class="button  | avis-btn" style="justify-self: center;">Donner un avis</button>
                <?php endif;
            elseif ($covoiturage['statut'] == 'programmé'): ?>
                    <form action="inc/covoiturage_action.php" method="POST"  style="justify-self: center;">
                        <input type="hidden" name="action" value="participer">
                        <input type="hidden" name="ID_covoiturage" value="<?php echo $ID_covoiturage; ?>">
                        <button class="button">Participer</button>
                    </form>
            <?php endif;
    } else: ?>
        <button class="button | connexion-btn" style="justify-self: center;">Participer</button>
    <?php endif; ?>

    <!-- Donner Avis -->
     <div class="donner-avis | hidden">
        <form action="inc/donner_avis.php" method="POST" class="avis-form">
        <h2>Laisser un avis</h2>
        <div class="avis-container">
            <div class="note">
                <label for="note">Note: </label>
                <div class="range">
                    <input type="range" name="note" id="note" min="1" max="5" step="1" default="3">
                    <p><output id="value"></output></p>
                    <span><img src="img/svg/star.svg" alt="Star"></span>
                </div>
            </div>
            <div class="description">
                <label for="description">Description: </label>
                <textarea name="description" id="description"></textarea>
            </div>
            <input type="hidden" name="donner_avis" value="1">
            <input type="hidden" name="chauffeur_id" value="<?php echo $chauffeurID; ?>">
            <input type="hidden" name="utilisateur_id" value="<?php echo $userID; ?>">
            <input type="hidden" name="covoiturage_id" value="<?php echo $ID_covoiturage; ?>">
            <button type="submit" class="button">Donner avis</button>
        </div>
        </form>
     </div>

<?php
$sqlAvis = '
SELECT
compte.pseudo,
avis.description,
avis.etoiles,
avis.statut
FROM avis
JOIN vehicule ON avis.ID_chauffeur = vehicule.ID_chauffeur
JOIN compte ON avis.ID_passager = compte.ID
JOIN covoiturage ON covoiturage.ID_vehicule = vehicule.ID_vehicule
WHERE covoiturage.ID_covoiturage = ? AND avis.statut = "validé"';
    
$stmtAvis = $conn->prepare($sqlAvis);
if($stmtAvis === false) {
    die('Erreur lors de la préparation de la requête : ' . $conn->error);
}
    
$stmtAvis->bind_param('i', $ID_covoiturage);
$stmtAvis->execute();
$resultAvis = $stmtAvis->get_result();
    
if ($resultAvis->num_rows > 0) { ?>
    <section class="splide">
        <div class="avis | splide__track | padding">
            <h2>Avis du Chauffeur</h2>
            <ul class="splide__list">
            <?php
            while ($avis = $resultAvis->fetch_assoc()) {
                $avisPseudo = htmlspecialchars($avis['pseudo']);
                $avisDescription = htmlspecialchars($avis['description']);
                $avisEtoiles = htmlspecialchars($avis['etoiles']);
                ?>
                
                <li class="splide__slide">
                <div class="avis-card">
                    <p class="avis-pseudo"><?php echo $avisPseudo ?></p>
                    <p class="avis-description">“<?php echo $avisDescription ?>”</p>
                    <span class="avis-etoiles">
                        <p><?php echo $avisEtoiles ?></p>
                        <img src="img/svg/star.svg" alt="">
                    </span>
                </div>
                </li>


            <?php
            }
            ?>
            </ul>
            </div>
    </section>

<?php
}
    
$stmt->close();
$conn->close(); ?>
</main>
<?php endif; ?>


</body>
<?php include 'inc/footer.inc.php'; ?>
<script src="script/modal.js"></script>
<script src="script/reveal-sections.js"></script>
<script src="script/connexion.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
        const avisBtn = document.querySelector('.avis-btn');
        const avis = document.querySelector('.donner-avis')
        const value = document.getElementById("value");
        const input = document.getElementById("note");
        document.addEventListener( 'DOMContentLoaded', function() {
          var splide = new Splide( '.splide' );
          splide.mount();
        } );

        value.textContent = input.value;
        input.addEventListener("input", (event) => {
            value.textContent = event.target.value;
        });

        avisBtn.addEventListener('click', function() {
            overlay.classList.remove('hidden');
            avis.classList.remove('hidden');

        });
      </script>
</body>
</html>