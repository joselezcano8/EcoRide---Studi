<?php

$pageTitle = 'Covoiturages';
$headerImg = 'img/travel.jpg';
$titleColor = '--clr-clear';
$firstTitle = 'Trouver un voyage';
$secondTitle = '';
include 'inc/header.inc.php';
?>

        <main class="main">
            <?php include 'inc/itineraire.inc.php'; ?>
            <section class="no-voyage | hidden">
                <p>Veuillez indiquer le lieu de départ, d'arrivée, la date et le nombre de passagers...</p>
            </section>
            <section class="voyages">
                <button class="button">Filtres</button>
                <div class="card">
                    <div class="card-chauffeur">  
                        <img src="img/svg/user.svg" alt="">
                        <p>Pseudo du chauffeur</p>
                    </div>
                    <div class="card-star">
                        <img src="img/svg/star.svg" alt="">
                        <p>4/5</p>
                    </div>
                    <div class="card-horaire">
                        <p>16h00 - 19h00</p>
                    </div>
                    <div class="card-car">
                        <img src="img/svg/car.svg" alt="">
                        <p>4 places</p>
                    </div>
                    <div class="card-eco">
                        <img src="img/svg/feuille.svg" alt="">
                        <p>Oui</p>
                    </div>
                    <div class="card-price">
                        <h2>6 Credits</h2>
                    </div>
                    <div class="card-button">
                        <button class="button">Détail</button>
                    </div>
                </div>
            </section>
        </main>

        <?php include 'inc/footer.inc.php'; ?>
            <script src="script-itineraire.js"></script>
            <script src="modal.js"></script>
    </body>
</html>