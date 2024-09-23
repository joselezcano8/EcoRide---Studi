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
    <main class="creation-compte | section">
        <form action="" class="form-cration-compte">
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
    <?php include 'inc/footer.inc.php'; ?>
    <script src="script/reveal-sections.js"></script>
</body>
</html>