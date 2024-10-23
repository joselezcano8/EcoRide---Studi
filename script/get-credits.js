'use strict';

document.addEventListener('DOMContentLoaded', function() {
    fetch('../inc/get-credits.php')
    .then(response => response.json())
    .then(data => {
        const labels = data.map(item => item.date);
        const totalCredits = data.map(item => item.total_credits);


        const ctx = document.getElementById('creditsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Crédits accumulés',
                    data: totalCredits,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2
                        }
                    }
                }
            }
        });

        document.querySelector('.totalCredits').textContent = `Nombre total de crédit gagné par la plateforme: ${totalCredits[totalCredits.length - 1]} Crédits.`
    })
    .catch(error => console.error('Erreur:', error));
})