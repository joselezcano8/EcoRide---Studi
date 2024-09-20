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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="<?php echo $pageTitle; ?>.css">
</head>
<body>
    <?php include 'inc/nav.inc.php' ?>
    <!-- Titre, sous-titre et bg image -->
    <header class="header" style="background-image: url('<?php echo $headerImg; ?>'); height: 450px; background-repeat: no-repeat">
        <h1 style="color: var(<?php echo $titleColor; ?>)"><?php echo $firstTitle; ?></h1>
        <h3 style="color: var(<?php echo $titleColor; ?>)"><?php echo $secondTitle; ?>.</h3>
        <!--Menu-->
    </header>