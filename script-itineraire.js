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

    function handleInput(inputElement, suggestionsElement) {
        inputElement.addEventListener('input', () => {
            const query = inputElement.value.toLowerCase();
            suggestionsElement.innerHTML = '';

            if (query) {
                const filteredCities = cities.filter(city => city.toLowerCase().includes(query));
                filteredCities.forEach(city => {
                    const li = document.createElement('li');
                    li.textContent = city;
                    li.addEventListener('click', () => {
                        inputElement.value = city;
                        suggestionsElement.innerHTML = '';
                    });
                    suggestionsElement.appendChild(li);
                });
            }
        });
    }

    handleInput(departInput, departSuggestions);
    handleInput(arriveInput, arriveSuggestions);

    document.addEventListener('click', (event) => {
        if (!departInput.contains(event.target) && !departSuggestions.contains(event.target) &&
            !arriveInput.contains(event.target) && !arriveSuggestions.contains(event.target)) {
            departSuggestions.innerHTML = '';
            arriveSuggestions.innerHTML = '';
        }
    });
});