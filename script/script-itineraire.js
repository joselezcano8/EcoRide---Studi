// script.js
document.addEventListener('DOMContentLoaded', () => {
    const departInput = document.getElementById('depart-input');
    const arriveInput = document.getElementById('arrive-input');
    const departSuggestions = document.getElementById('depart-suggestions');
    const arriveSuggestions = document.getElementById('arrive-suggestions');

    // Array de ciudades francesas
    const cities = [
        'Paris',
        'Marseille',
        'Lyon',
        'Toulouse',
        'Nice',
        'Nantes',
        'Montpellier',
        'Strasbourg',
        'Bordeaux',
        'Lille',
        'Rennes',
        'Reims',
        'Le Havre',
        'Saint-Étienne',
        'Toulon',
        'Angers',
        'Grenoble',
        'Dijon',
        'Nîmes',
        'Aix-en-Provence',
        'La Rochelle',
        'Clermont-Ferrand',
        'Le Mans',
        'Amiens',
        'Tours',
        'Perpignan',
        'Metz',
        'Caen',
        'Boulogne-Billancourt',
        'Nancy',
        'Saint-Denis',
        'Pau',
        'Neuilly-sur-Seine',
        'La Seyne-sur-Mer'
    ];

    cities.forEach(city => {
        const option = document.createElement('option')
        option.value = city
        departSuggestions.appendChild(option)
    });

    cities.forEach(city => {
        const option = document.createElement('option')
        option.value = city
        arriveSuggestions.appendChild(option)
    })
    console.log(departSuggestions, arriveSuggestions);
});