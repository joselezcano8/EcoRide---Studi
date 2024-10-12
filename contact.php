<?php
$pageTitle = 'Contact';
include 'inc/nav.inc.php';
include 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Envoyer'])) {

    $nom = ucfirst(strtolower(trim($_POST['nom'])));
    $prenom = ucfirst(strtolower(trim($_POST['prenom'])));
    $courriel = strtolower(trim($_POST['courriel']));
    $message = strtolower($_POST['message']);

    $stmt = $conn->prepare('INSERT INTO contact (nom, prenom, courriel, message) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $nom, $prenom, $courriel, $message);
    
    if ($stmt->execute()) {
        $paragraph = 'Votre message a été envoyé avec succès !';
        $href = 'index.php';
        $button = 'Redirection vers l\'accueil';
        include 'inc/alert.inc.php';
    } else {
        $paragraph = 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.';
        $href = '';
        $button = 'Ok';
        include 'inc/alert.inc.php';
    };

    $stmt->close();
}
else echo 'aaaaaaaaa';

?>
        <main class="main | padding">
            <!-- Form de Contact -->
            <div class="contact | section">
                <h1>Nous Contacter</h1>
                <form class="contact-form" action="" method="POST">
                    <input type="text" placeholder="Nom" class="contact-name" name='nom' required>
                    <input type="text" placeholder="Prénom" class="contact-lastname" name="prenom" required>
                    <input type="email" name="courriel" id="" placeholder="Email" class="contact-email" required>
                    <textarea name="message" id="" placeholder="Message" class="contact-message" required></textarea required>
                    <input type="submit" name="Envoyer" value="Envoyer" class="button">
                </form>
            </div>
        </main>

        <!-- Footer et JavaScript -->
        <?php include 'inc/footer.inc.php'; ?>
        <script src="script/modal.js"></script>
        <script src="script/reveal-sections.js"></script>
        <script src="script/connexion.js"></script>
    </body>
</html>