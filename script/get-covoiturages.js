'use strict';

document.addEventListener('DOMContentLoaded', function() {
    fetch('../inc/get-covoiturages.php')
    .then(response => response.json())
    .then(data => {
        const labels = data.map(item => item.date);
        const values = data.map(item => parseInt(item.count, 10));

        const ctx = document.getElementById('covoituragesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Covoiturages',
                    data: values,
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
                            stepSize: 1
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            family: 'Noto Sans',  
                            size: 14,          
                            weight: 'bold'     
                        }
                    }
                },
            }
        });
    })
    .catch(error => console.error('Erreur:', error));
});