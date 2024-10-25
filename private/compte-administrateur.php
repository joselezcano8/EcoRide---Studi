<?php
session_start();

if ($_SESSION['role'] !== 'Administrateur') {
    header('Location: ../index.php');
    exit;
}

include '../inc/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_compte']) && !isset($_POST['confirm_suspend'])) {
    $_SESSION['id_compte_to_suspend'] = $_POST['id_compte'];
}

$response = ['success' => false, 'error' => ''];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adresse-mail']) && isset($_POST['pseudo'])) {
    $courriel = filter_var(trim($_POST['adresse-mail']), FILTER_SANITIZE_EMAIL);
    $pseudo = trim($_POST['pseudo']);
    $password = trim($_POST['password-creation']);
    $passwordRepeat = trim($_POST['password-repeat']);

    $stmt = $conn->prepare('SELECT * FROM compte WHERE courriel = ? OR pseudo = ?');
    $stmt->bind_param('ss', $courriel, $pseudo);
    $stmt->execute();
    $result = $stmt->get_result();

    $paragraph = '';
    $href = '';
    $button = 'Ok';

    if ($result->num_rows > 0) {
        $paragraph = 'Ce pseudonyme ou email est déjà utilisé. Veuillez en choisir un autre.';
        include '../inc/alert.inc.php';
    } else {
        if ($password === $passwordRepeat) {
            if (preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare('INSERT INTO compte (courriel, pseudo, mot_de_passe, role) VALUES (?, ?, ?, "Employé")');
                $stmt->bind_param('sss', $courriel, $pseudo, $hashedPassword);

                if ($stmt->execute()) {
                    $compteID = $conn->insert_id;

                    $stmt2 = $conn->prepare('INSERT INTO utilisateur (ID) VALUES (?)');
                    $stmt2->bind_param('i', $compteID);

                    if ($stmt2->execute()) {
                        $paragraph = 'Compte créé avec succès.';
                    } else {
                        $paragraph = 'Une erreur est survenue lors de la création de l\'utilisateur. Veuillez réessayer plus tard.';
                    }

                    $stmt2->close();
                } else {
                    $paragraph = 'Une erreur est survenue. Veuillez réessayer plus tard.';
                }

                include '../inc/alert.inc.php';
            } else {
                $paragraph = 'Le mot de passe doit contenir au moins 8 caractères, un caractère spécial, une lettre majuscule et un chiffre.';
                include '../inc/alert.inc.php';
            }
        } else {
            $paragraph = 'Les mots de passe ne correspondent pas';
            include '../inc/alert.inc.php';
        }
    }
}

if (isset($_POST['confirm_suspend'])) {
    $idCompte = $_POST['id_compte'];

    $stmt = $conn->prepare('UPDATE compte SET role = "Suspendu" WHERE ID = ?');
    $stmt->bind_param('i', $idCompte);

    $stmt2 = $conn->prepare('UPDATE covoiturage c 
                             JOIN vehicule v ON v.ID_vehicule = c.ID_vehicule 
                             SET c.statut = "Annulé" 
                             WHERE v.ID_chauffeur = ?');                  
    $stmt2->bind_param('i', $idCompte);

    $stmt3 = $conn->prepare('DELETE FROM participants WHERE ID_utilisateur = ?');
    $stmt3->bind_param('i', $idCompte);

    if ($stmt->execute()) {

        $stmt2->execute();
        $stmt3->execute();

        $paragraph = 'Compte suspendu avec succés';
        $href = '';
        $button = 'Ok';

        include '../inc/alert.inc.php';
    }

    unset($_SESSION['id_compte_to_suspend']);
}

if (isset($_POST['cancel_suspend'])) {
    unset($_SESSION['id_compte_to_suspend']);
}

$pageTitle = 'Page Administrateur';
$pageCss = 'compte-admin';
include '../inc/nav.inc.employe.php';

?>

<main class="main">
    <h1>Espace Administrateur</h1>  
    <section class="graphiques">
        <div class="chart">
            <h2>Nombre de Covoiturages par Jour</h2>
            <canvas id="covoituragesChart"></canvas>
        </div>

        <div class="chart">
            <h2>Nombre de Crédits en fonction des jours</h2>
            <canvas id="creditsChart"></canvas>
            <p class="totalCredits">aaa</p>
        </div>
    </section>

    <section class="creation-comptes">
        <h2>Création de compte employé</h2>
        <form action="" class="form-creation-compte" method="POST">
            <div>
                <label for="adresse-mail"><p>Saisir adresse mail</p></label>
                <input type="email" name="adresse-mail" id="adresse-mail" placeholder="adresse@exemple.com" required>
            </div>
            <div>
                <label for="pseudo"><p>Saisir un pseudo</p></label>
                <input type="text" name="pseudo" id="pseudo" placeholder="pseudo_exemple123" required>
            </div>
            <div>
                <label for="password-creation"><p>Choisissez un mot de passe</p></label>
                <input type="password" name="password-creation" id="password-creation" placeholder="**********" required>
            </div>
            <div>
                <label for="password-repeat"><p>Répéter le mot de passe</p></label>
                <input type="password" name="password-repeat" id="password-repeat" placeholder="**********" required>
            </div>

            <input type="submit" value="S'inscrire" class="button">
        </form>
    </section>

    <section class="suspendre-compte">
        <h2>Suspendre un compte</h2>
        <form action="" method="POST" class="suspendre-compte-form">
            <label for="id_compte"><p>Entrez l'ID du compte que vous souhaitez suspendre</p></label>
            <div>
                <input type="number" name="id_compte" id="id_compte" min=100 required>
                <input type="submit" value="Suspendre" class="button">
            </div>
        </form>
    </section>

    <!-- Modal de confirmation -->
    <?php
    if (isset($_SESSION['id_compte_to_suspend']) && !isset($_POST['confirm_suspend'])) {
        $idCompte = $_SESSION['id_compte_to_suspend'];
        echo "
        <div class='modal'>
            <div class='modal-content'>
                <p>Êtes-vous sûr de vouloir suspendre le compte ID: $idCompte ?</p>
                <br>
                <form method='POST' action=''>
                    <input type='hidden' name='id_compte' value='$idCompte'>
                    <input type='submit' name='confirm_suspend' value='Oui' class='button'>
                    <input type='submit' name='cancel_suspend' value='Non' class='button'>
                </form>
            </div>
        </div>";
    }
    ?>
</main>

<!-- Footer -->
<?php include '../inc/footer.inc.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../script/get-covoiturages.js"></script>
<script src="../script/get-credits.js"></script>
</body>

<style>
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    text-align: center;
}

.modal-buttons {
    margin-top: 20px;
    display: flex;
    justify-content: space-around;
}
</style>
