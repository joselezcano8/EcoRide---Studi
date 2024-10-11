<?php

$pageTitle = 'Accueil';
$headerImg = 'img/car_road.jpg';
$titleColor = '--clr-clear';
$firstTitle = 'EcoRide';
$secondTitle = 'Votre trajet, notre planète.';
/* Header */
include 'inc/header.inc.php';

?>

    <main class="main"> 
        <!-- Itineraire -->
        <?php include 'inc/itineraire.inc.php'; ?>
        <!--Sections-->
        <section class="section | objectif | padding">
                <img src="img/covoiturage.jpg" alt="Image du covoiturage">
                <div class="section-text">
                    <h2>Notre Objectif</h2>
                    <p>Chez EcoRide, nous visons à réduire l'impact environnemental des déplacements en facilitant le covoiturage. Notre plateforme offre une solution écologique et économique pour voyager ensemble, tout en préservant notre planète.</p>
                </div>
            </section>
            <section class="section | covoiturages | padding">
                <div class="section-text">
                    <h2>Tous nos conducteurs sont vérifiés</h2>
                    <p>Chez EcoRide, la sécurité et la confiance sont primordiales. C’est pourquoi tous nos conducteurs sont soigneusement vérifiés avant de rejoindre notre plateforme. Vous pouvez voyager en toute sérénité, sachant que chaque conducteur répond à nos standards rigoureux de qualité et de sécurité.</p>
                    <a href="covoiturages.php"><button class="button">Covoiturages</button></a>
                </div>
                <img src="img/woman_smiling.jpg" alt="">
            </section>
            <section class="contactez-nous">
                <h2>Contactez-nous</h2>
                <p>Pour toute question ou demande d'information, n'hésitez pas à nous contacter. Nous sommes là pour vous aider et nous nous engageons à répondre à vos besoins dans les plus brefs délais.</p>
                <a href="contact.php"><button class="button">Nous Contacter</button></a>
            </section>
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