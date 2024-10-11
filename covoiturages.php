<?php
$pageTitle = 'Covoiturages';
$headerImg = 'img/travel.jpg';
$titleColor = '--clr-clear';
$firstTitle = 'Trouver un voyage';
$secondTitle = '';
/* Header */
include 'inc/header.inc.php';
?>

        <main class="main | padding">
            <!-- Itineraire -->
            <?php include 'inc/itineraire.inc.php'; ?>
            <section class="voyages | section">
                <?php include 'inc/card.sql.inc.php'; ?>

            </section>
        </main>

        <!-- Footer -->
        <?php include 'inc/footer.inc.php'; ?>

        <!-- JavaScript -->
            <script src="script/script-itineraire.js"></script>
            <script src="script/modal.js"></script>
            <script src="script/reveal-sections.js"></script>
            <script src="script/connexion.js"></script>
            <script src="script/range.js"></script>
            <!-- <script>window.history.replaceState({}, document.title, window.location.pathname);</script> -->
    </body>
</html>