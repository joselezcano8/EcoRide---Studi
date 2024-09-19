<?php

$pageTitle = 'Covoiturages';
$headerImg = 'img/travel.jpg';
$titleColor = '--clr-clear';
$firstTitle = 'Trouver un voyage';
$secondTitle = '';
include 'inc/header.inc.php';
?>

        <main class="main | padding">
            <?php include 'inc/itineraire.inc.php'; ?>
            <section class="no-voyage | hidden">
                <p>Veuillez indiquer le lieu de départ, d'arrivée, la date et le nombre de passagers...</p>
            </section>
            <section class="voyages">
                <button class="button">Filtres</button>
                <div class="cards">
                <?php include 'inc/card.inc.php'; ?>
                <?php include 'inc/card.inc.php'; ?>
                <?php include 'inc/card.inc.php'; ?>
                <?php include 'inc/card.inc.php'; ?>
                </div>
            </section>
        </main>

        <?php include 'inc/footer.inc.php'; ?>
            <script src="script-itineraire.js"></script>
            <script src="modal.js"></script>
    </body>
</html>