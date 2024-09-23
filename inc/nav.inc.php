<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide &bull; <?php echo $pageTitle; ?></title>
    <!--Font Roboto-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!--Font Noto Sans-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/<?php echo $pageTitle; ?>.css">
</head>
<body>
    <!--Overlay-->
    <div class="overlay | hidden"></div> 
    <!-- Section de la barre de navigation -->
    <nav class="nav | padding">
        <a href="index.php">
            <img class="logo" src="img/logo_primary.png" alt="Logo Eco Ride">
        </a>
        <!-- Hamburguer Menu-->
        <button class="mobile-open-modal">
            <span>
                <img src="img/svg/icon-hamburger.svg" alt="Open Menu">
            </span>
        </button>
        <!-- Nav List -->
        <ul class="nav-links" id="nav-links">
            <a href="index.php">Accueil</a>
            <a href="covoiturages.php">Covoiturages</a>
            <a href="contact.php">Contact</a>
            <button class="button | connexion-btn">Connexion</button>
        </ul>
    </nav>
    <!-- Menu -->
    <div class="menu | hidden">
        <button>
            <span class="mobile-close-modal">
                 <img src="img/svg/icon-close.svg" alt="Close Menu">
            </span>
        </button>
        <ul class="mobile-nav-links">
            <a href="index.php">Accueil</a>
            <a href="covoiturages.php">Covoiturages</a>
            <a href="contact.php">Contact</a>
            <a class="connexion-link">Connexion</a>
        </ul>
    </div>
    <!-- Connexion Menu -->
     <div class="connexion-menu | hidden">
        <h2>Connexion</h2>
        <form action="" class="connexion-form">
            <div class="connexxion-form-utilisateur">
                <label for="connexion-utilisateur">Nom'dutilisateur</label>
                <input type="email" placeholder="Email ou ID" id="connexion-utilisateur" class="connexion-utilisateur">
            </div>

            <div class="connexion-form-password">
                <label for="connexion-password">Mot de passe</label>
                <input type="password" placeholder="Mot de passe" id="connexion-password" class="connexion-password">
            </div>

            <input type="submit" value="Se connecter" class="button">
        </form>
        <a href="creation-compte.php">Pas encore de compte?</a>
     </div>