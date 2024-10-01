document.getElementById('connexion-form').addEventListener('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch('inc/connexion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.succes) {
            location.reload();
        } else {
            document.getElementById('error-message').textContent = data.error;
        }
    })
    .catch(error => console.error('Error', error));
    location.reload();
})