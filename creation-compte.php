<?php 

include 'inc/config.php';

$response = ['success' => false, 'error' => ''];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courriel = filter_var(trim($_POST['adresse-mail']), FILTER_SANITIZE_EMAIL);
    $pseudo = trim($_POST['pseudo']);
    $password = trim($_POST['password-creation']);
    $passwordRepeat = trim($_POST['password-repeat']);

    $stmt = $conn->prepare('SELECT * FROM compte WHERE courriel = ? OR pseudo = ?');
    $stmt->bind_param('ss', $email, $pseudo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $paragraph = '';
    $href = '#';
    $button = 'Ok';

    if ($result->num_rows > 0) {
        $paragraph = 'Ce pseudonyme ou email est déjà utilisé. Veuillez en choisir un autre.';
        include 'inc/alert.inc.php';
    } else {
        if ($password === $passwordRepeat) {
            if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare('INSERT INTO compte (courriel, pseudo, mot_de_passe) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $courriel, $pseudo, $hashedPassword);



                if ($stmt->execute()) {
                    $paragraph = 'Compte créé avec succès ! Redirection vers la page d\'accueil...';
                    $href = 'index.php';
                    $button = 'Redirection vers la page d\'accueil';
                    include 'inc/alert.inc.php';
                } else {
                    $paragraph = 'Une erreur est survenue. Veuillez réessayer plus tard..';
                    include 'inc/alert.inc.php';
                }
            } else {
                    $paragraph = 'Le mot de passe doit contenir au moins 8 caractères, un caractère spécial, une lettre majuscule et un chiffre.';
                    include 'inc/alert.inc.php';
            }
        } else {
            $paragraph = 'Les mots de passe ne correspondent pas';
            include 'inc/alert.inc.php';
        }
    }
    
    $stmt->close();
    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide &bull; Creation de Compte</title>
    <!--Font Roboto-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!--Font Noto Sans-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/creation-compte.css">
</head>
<body>
    <header class="header | padding">
        <a href="index.php">
            <img class="logo" src="img/logo_primary.png" alt="Logo Eco Ride">
        </a>
    </header>
    
    <!-- Form de creation de compte -->
    <main class="creation-compte | section">
        <form action="" class="form-cration-compte" method="POST">
        <h1>Creation de compte</h1>
            <div>
                <label for="adresse-mail">Veulliez sauisir votre adresse mail</label>
                <input type="email" name="adresse-mail" id="adresse-mail" placeholder="adresse@exemple.com">
            </div>
            <div>
                <label for="pseudo">Saisir un pseudo</label>
                <input type="text" name="pseudo" id="pseudo" placeholder="pseudo_exemple123">
            </div>
            <div>
                <label for="password-creation">Choisissez un mot de passe</label>
                <input type="password" name="password-creation" id="password-creation" placeholder="**********">
            </div>
            <div>
                <label for="password-repeat">Répéter le mot de passe</label>
                <input type="password" name="password-repeat" id="password-repeat" placeholder="**********">
            </div>

            <input type="submit" value="S'inscrire" class="button">
        </form>
    </main>

    <!-- Footer et JavaScript -->
    <?php include 'inc/footer.inc.php'; ?>
    <script src="script/reveal-sections.js"></script>
    <script src="script/modal.js"></script>
</body>
</html>