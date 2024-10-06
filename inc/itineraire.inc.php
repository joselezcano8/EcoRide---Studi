<form class="form" id="form-recherche" action="covoiturages.php" method="GET">
    <div>
        <input type="text" name="depart" id="depart-input" list="depart-suggestions" placeholder="Départ" class="depart" value="<?php echo isset($_GET['depart']) ? $_GET['depart'] : ''; ?>">
        <datalist id="depart-suggestions"></datalist>
                
        <input type="text" name="arrive" id="arrive-input" list="arrive-suggestions" placeholder="Arrive" class="arrive" value="<?php echo isset($_GET['arrive']) ? $_GET['arrive'] : ''; ?>">
        <datalist id="arrive-suggestions"></datalist>
                
        <input type="date" name="date" placeholder="Date" class="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
                
        <input type="number" name="passagers" id="nombre-passagers" min="1" placeholder="Nombre de passagers" class="nombre-passagers" value="<?php echo isset($_GET['passagers']) ? $_GET['passagers'] : ''; ?>">
    </div>

    <input type="hidden" name="eco" value="<?php echo isset($_GET['eco']) ? $_GET['eco'] : ''; ?>">
    <input type="hidden" name="prix" value="<?php echo isset($_GET['prix']) ? $_GET['prix'] : ''; ?>">
    <input type="hidden" name="duree" value="<?php echo isset($_GET['duree']) ? $_GET['duree'] : ''; ?>">
    <input type="hidden" name="note" value="<?php echo isset($_GET['note']) ? $_GET['note'] : 1; ?>">

    <input type="submit" value="Rechercher" class="button | rechercher">
</form>