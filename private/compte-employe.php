<?php session_start(); 

include '../inc/config.php';

$stmt = $conn->prepare('SELECT
avis.ID_avis,
avis.ID_passager,
avis.ID_chauffeur,
avis.date,
avis.etoiles,
avis.description,
c.lieu_depart,
c.lieu_arrivee
FROM avis
JOIN covoiturage c ON avis.ID_covoiturage = c.ID_covoiturage
WHERE avis.statut = "en attente"');
$stmt->execute();
$avis = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $avis_id = $_POST['avis_id'];
    $action = $_POST['action'];

    if (isset($avis_id) && ($action === 'valider' || $action === 'refuser')) {
        $nouveau_statut = ($action === 'valider') ? 'validé' : 'refusé';

        $stmt = $conn->prepare('UPDATE avis SET statut = ? WHERE ID_avis = ?');
        $stmt->bind_param('si', $nouveau_statut, $avis_id);


        $href = '';
        $button = 'Ok';
        if ($stmt->execute()) {
            $paragraph = "L'avis a été " . ($action === 'valider' ? "validé" : "rejeté") . " avec succès.";
            include '../inc/alert.inc.php';

            if ($action === 'valider') {
                $stmt = $conn->prepare('SELECT ID_chauffeur FROM avis WHERE ID_avis = ?');
                $stmt->bind_param('i', $avis_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $chauffeur_id = $result->fetch_assoc()['ID_chauffeur'];
                $stmt->close();

                mettreAJourNoteChauffeur($chauffeur_id, $conn);
            }

        } else {
            $paragraph = "Erreur lors de la mise à jour de l'avis.";
            include '../inc/alert.inc.php';
        }
    } else {
            $paragraph = "Données invalides";
            include '../inc/alert.inc.php';
    }
}

function mettreAJourNoteChauffeur($chauffeur_id, $conn) {
    $stmt = $conn->prepare('SELECT AVG(etoiles) AS note_moyenne FROM avis WHERE ID_chauffeur = ? AND statut = "validé"');
    $stmt->bind_param('i', $chauffeur_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $note_moyenne = $result->fetch_assoc()['note_moyenne'];
    $stmt->close();

    $note_moyenne_arrondie = round($note_moyenne);

    $stmt = $conn->prepare('UPDATE chauffeur SET note = ? WHERE ID = ?');
    $stmt->bind_param('ii', $note_moyenne_arrondie, $chauffeur_id);
    $stmt->execute();
    $stmt->close();

}

$pageTitle = 'Page Employée';
$pageCss = 'compte-employe';
include '../inc/nav.inc.employe.php'

?>
        <main class="main | padding">
            <h1>Espace Employé</h1>
            <h2>Gestion d'avis</h2>

            <?php if ($avis): ?>
                <div class="table-container">

                    <div class="table-header">ID Passager</div>
                    <div class="table-header">ID Chauffeur</div>
                    <div class="table-header">Date</div>
                    <div class="table-header">Note</div>
                    <div class="table-header">Départ</div>
                    <div class="table-header">Arrivée</div>
                    <div class="table-header">Description</div>
                    <div class="table-header actions">Actions</div>


                    <?php foreach ($avis as $row): ?>
                        <div class="table-cell"><?php echo htmlspecialchars($row['ID_passager']) ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($row['ID_chauffeur']) ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($row['date']) ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($row['etoiles']) ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($row['lieu_depart']) ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($row['lieu_arrivee']) ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($row['description']) ?></div>
                        <div class="table-cell table-cell-button">
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="avis_id" value="<?php echo htmlspecialchars($row['ID_avis']); ?>">
                                <button type="submit" name="action" value="valider" class="button button-valider">Valider</button>
                            </form>
                        </div>
                        <div class="table-cell table-cell-button">
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="avis_id" value="<?php echo htmlspecialchars($row['ID_avis']); ?>">
                                <button type="submit" name="action" value="refuser" class="button button-refuser">Refuser</button>
                            </form>
                        </div>
                    <?php endforeach;?>
                </div>
            <?php else: ?>
                <p>Il n'y a aucun avis en attente de validation</p>
            <?php endif; ?>
        </main>

    <!-- Footer -->
        <?php include '../inc/footer.inc.php'; ?>
    </body>
</html>