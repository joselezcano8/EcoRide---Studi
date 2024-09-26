'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const departSuggestions = document.getElementById('depart-suggestions');
    const arriveSuggestions = document.getElementById('arrive-suggestions');


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
});
