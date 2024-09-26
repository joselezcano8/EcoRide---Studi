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
            <section class="voyages | section">
                <button class="button">Filtres</button>
                <?php include 'inc/card.sql.inc.php'; ?>

            </section>
        </main>

        <?php include 'inc/footer.inc.php'; ?>
            <script src="script/script-itineraire.js"></script>
            <script src="script/modal.js"></script>
            <script src="script/reveal-sections.js"></script>
            <script>window.history.replaceState({}, document.title, window.location.pathname);</script>

    </body>
</html>