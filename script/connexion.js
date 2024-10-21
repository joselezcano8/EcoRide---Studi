document.getElementById('connexion-form').addEventListener('submit', function(e) {
    // Empêcher l'envoi du formulaire par défaut
    e.preventDefault();

    // Créer un FormData avec les données du formulaire
    var formData = new FormData(this);

    // Envoyer les données au serveur via une requête fetch POST
    fetch('inc/connexion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Si la connexion réussit, recharger la page
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            }
            else {
                location.reload();
            }
        } else {
            // Sinon, afficher un message d'erreur
            document.getElementById('error-message').textContent = data.error;
        }
    })
    .catch(error => console.error('Erreur', error)); // Gérer les erreurs de requête
});
