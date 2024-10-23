<?php session_start(); 

include '../inc/config.php';

$pageTitle = 'Page Administrateur';
$pageCss = 'compte-admin';
include '../inc/nav.inc.employe.php';

?>

    <main class="main">
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

        <section class="gerer-comptes">

        </section>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../script/get-covoiturages.js"></script>
    <script src="../script/get-credits.js"></script>
</body>