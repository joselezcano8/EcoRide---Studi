<form class="form" id="form-recherche" action="covoiturages.php" method="POST">
    <div>
        <input type="text" name="depart" id="depart-input" list="depart-suggestions" placeholder="Départ" class="depart" value="<?php echo isset($_POST['depart']) ? $_POST['depart'] : ''; ?>">
        <datalist id="depart-suggestions"></datalist>
                
        <input type="text" name="arrive" id="arrive-input" list="arrive-suggestions" placeholder="Arrive" class="arrive" value="<?php echo isset($_POST['arrive']) ? $_POST['arrive'] : ''; ?>">
        <datalist id="arrive-suggestions"></datalist>
                
        <input type="date" name="date" placeholder="Date" class="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>">
                
        <input type="number" name="passagers" id="nombre-passagers" min="1" placeholder="Nombre de passagers" class="nombre-passagers" value="<?php echo isset($_POST['passagers']) ? $_POST['passagers'] : ''; ?>">
    </div>

    <input type="hidden" name="eco" value="<?php echo isset($_POST['eco']) ? $_POST['eco'] : ''; ?>">
    <input type="hidden" name="prix" value="<?php echo isset($_POST['prix']) ? $_POST['prix'] : ''; ?>">
    <input type="hidden" name="duree" value="<?php echo isset($_POST['duree']) ? $_POST['duree'] : ''; ?>">
    <input type="hidden" name="note" value="<?php echo isset($_POST['note']) ? $_POST['note'] : 1; ?>">

    <input type="submit" value="Rechercher" class="button | rechercher">
</form>