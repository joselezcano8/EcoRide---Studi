<?php

//Connexion
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ecoride';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn -> connect_error) {
    die($conn->connect_error);
}

$sql = '
SELECT
     c.ID_covoiturage,
     compte.pseudo AS chauffeur_pseudo,
     chauffeur.note AS chauffeur_note,
     c.heure_depart,
     c.heure_arrivee,
     c.places_disponibles,
     vehicule.eco AS vehicule_eco,
     c.prix
FROM
    Covoiturage c
JOIN
    Vehicule vehicule ON c.ID_vehicule = vehicule.ID_vehicule
JOIN
    Chauffeur chauffeur ON vehicule.ID_chauffeur = chauffeur.ID
JOIN
    Compte compte ON chauffeur.ID = compte.ID
WHERE
    c.statut = "programme"
ORDER BY
    c.date, c.heure_depart
';

$result = $conn->query($sql);


if($result->num_rows > 0) {
    while($row = $result->fetch_assoc())
 {
    $chauffeurPseudo = htmlspecialchars($row['chauffeur_pseudo']);
    $chauffeurNote = htmlspecialchars($row['chauffeur_note']);
        $heureDepart = htmlspecialchars($row['heure_depart']);
        $heureArrivee = htmlspecialchars($row['heure_arrivee']);
        $placesDisponibles = htmlspecialchars($row['places_disponibles']);
        $vehiculeEco = $row['vehicule_eco'] ? 'Oui' : 'Non';
        $prix = htmlspecialchars($row['prix']);
        $ID_covoiturage = htmlspecialchars($row['ID_covoiturage']);
    ?>

    <div class="card">
        <div class="card-chauffeur">  
            <img src="img/svg/user.svg" alt="">
            <p><?php echo $chauffeurPseudo; ?></p>
        </div>
        <div class="card-star">
            <img src="img/svg/star.svg" alt="">
            <p><?php echo $chauffeurNote; ?>/5</p>
        </div>
        <div class="card-horaire">
            <p><?php echo $heureDepart; ?> - <?php echo $heureArrivee; ?></p>
        </div>
        <div class="card-car">
            <img src="img/svg/car.svg" alt="">
            <p><?php echo $placesDisponibles; ?> places</p>
        </div>
        <div class="card-eco">
            <img src="img/svg/feuille.svg" alt="">
            <p><?php echo $vehiculeEco; ?></p>
        </div>
        <div class="card-price">
            <h2><?php echo $prix; ?> Credits</h2>
        </div>
        <div class="card-button">
            <button class="button">Détail</button>
        </div>
    </div>

    <?php
}} else {
    echo 'Error';
}
$conn->close();
?>