<form class="form" id="form-recherche" action="covoiturages.php" method="GET">
    <div>
        <input type="text" name="depart" id="depart-input" list="depart-suggestions" placeholder="Départ" class="depart">
        <datalist id="depart-suggestions"></datalist>
                
        <input type="text" name="arrive" id="arrive-input" list="arrive-suggestions" placeholder="Arrive" class="arrive">
        <datalist id="arrive-suggestions"></datalist>
                
        <input type="date" name="date" placeholder="Date" class="date">
                
        <input type="number" name="passagers" id="nombre-passagers" min="1" default="1" placeholder="Nombre de passagers" class="nombre-passagers">
        </div>

        <input type="submit" value="Rechercher" class="button | rechercher">
</form>