<?php
$pageTitle = 'Contact';
include 'inc/nav.inc.php';
?>

        <main class="main | padding">
            <div class="contact | section">
                <h1>Nous Contacter</h1>
                <form class="contact-form" action="">
                    <input type="text" placeholder="Nom" class="contact-name">
                    <input type="text" placeholder="Prénom" class="contact-lastname">
                    <input type="email" name="" id="" placeholder="Email" class="contact-email">
                    <textarea name="" id="" placeholder="Message" class="contact-message"></textarea>
                    <input type="submit" value="Envoyer" class="button">
                </form>
            </div>
        </main>

        <?php include 'inc/footer.inc.php'; ?>
        <script src="script/script-itineraire.js"></script>
        <script src="script/modal.js"></script>
        <script src="script/reveal-sections.js"></script>
    </body>
</html>