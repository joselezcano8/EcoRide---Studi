'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const departInput = document.getElementById('depart-input');
    const arriveInput = document.getElementById('arrive-input');
    const departSuggestions = document.getElementById('depart-suggestions');
    const arriveSuggestions = document.getElementById('arrive-suggestions');
    const nombrePassagers = document.getElementById('nombre-passagers');
    const formRecherche = document.getElementById('form-recherche');
    const cardsContainer = document.querySelector('.cards');

    // Tableau des villes françaises
    const cities = [
        'Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes',
        'Montpellier', 'Strasbourg', 'Bordeaux', 'Lille', 'Rennes',
        'Reims', 'Le Havre', 'Saint-Étienne', 'Toulon', 'Angers',
        'Grenoble', 'Dijon', 'Nîmes', 'Aix-en-Provence', 'La Rochelle',
        'Clermont-Ferrand', 'Le Mans', 'Amiens', 'Tours', 'Perpignan',
        'Metz', 'Caen', 'Boulogne-Billancourt', 'Nancy', 'Saint-Denis',
        'Pau', 'Neuilly-sur-Seine', 'La Seyne-sur-Mer'
    ];

    // Ajouter les villes aux listes de suggestions
    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        departSuggestions.appendChild(option);
    });

    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        arriveSuggestions.appendChild(option);
    });

    // Gérer la soumission du formulaire de recherche
    formRecherche.addEventListener('submit', function(e) {
        e.preventDefault();

        const depart = departInput.value;
        const arrive = arriveInput.value;
        const passagers = nombrePassagers.value;

        const formData = new FormData();
        formData.append('depart', depart);
        formData.append('arrive', arrive);
        formData.append('passagers', passagers);

        fetch('inc/card.sql.inc.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showCovoiturages(result.data);
            } else {
                cardsContainer.innerHTML = `<p>Erreur : ${result.error}</p>`;
            }
        })
        .catch(error => {
            cardsContainer.innerHTML = `<p>Erreur : ${error.message}</p>`;
            console.error('Erreur dans la requête fetch :', error);
        });
    });

    // Fonction pour afficher les cartes de covoiturages
    function showCovoiturages(covoiturages) {
        cardsContainer.innerHTML = '';

        if (covoiturages.length > 0) {
            covoiturages.forEach(covoiturage => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.innerHTML = `
                <div class="card-chauffeur">  
                    <img src="img/svg/user.svg" alt="">
                    <p>${covoiturage.chauffeur_pseudo}</p>
                </div>
                <div class="card-star">
                    <img src="img/svg/star.svg" alt="">
                    <p>${covoiturage.chauffeur_note}/5</p>
                </div>
                <div class="card-horaire">
                    <p>${covoiturage.heure_depart} - ${covoiturage.heure_arrivee}</p>
                </div>
                <div class="card-car">
                    <img src="img/svg/car.svg" alt="">
                    <p>${covoiturage.places_disponibles} places</p>
                </div>
                <div class="card-eco">
                    <img src="img/svg/feuille.svg" alt="">
                    <p>${covoiturage.vehicule_eco ? 'Oui' : 'Non'}</p>
                </div>
                <div class="card-price">
                    <h2>${covoiturage.prix} Crédits</h2>
                </div>
                <div class="card-button">
                    <button class="button">Détail</button>
                </div>
                `;

                // Ajouter un événement au bouton "Détail"
                const detailButton = card.querySelector('.card-button .button');
                detailButton.addEventListener('click', () => {
                    window.location.href = `detail_covoiturage.php?ID_covoiturage=${covoiturage.ID_covoiturage}`;
                });

                cardsContainer.appendChild(card);
            });
        } else {
            cardsContainer.innerHTML = '<p>Aucun covoiturage disponible.</p>';
        }
    }
});
