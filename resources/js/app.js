import './bootstrap';
import Chart from 'chart.js/auto';

window.Chart = Chart;

function renderDashboardMovementChart() {
    const canvas = document.getElementById('dashboardMovementChart');

    if (!canvas) {
        return;
    }

    let movementSeries = [];

    try {
        movementSeries = JSON.parse(canvas.dataset.movementSeries || '[]');
    } catch (error) {
        movementSeries = [];
    }

    const existingChart = Chart.getChart(canvas);

    if (existingChart) {
        existingChart.destroy();
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: movementSeries.map((row) => row.day),
            datasets: [
                {
                    label: 'Entradas',
                    data: movementSeries.map((row) => row.entries),
                    backgroundColor: 'rgba(22,101,52,0.78)',
                    borderColor: '#166534',
                    borderWidth: 1,
                    borderRadius: 6,
                },
                {
                    label: 'Saídas',
                    data: movementSeries.map((row) => row.exits),
                    backgroundColor: 'rgba(153,27,27,0.72)',
                    borderColor: '#991b1b',
                    borderWidth: 1,
                    borderRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#1a3d1f',
                        font: {
                            weight: '600',
                        },
                    },
                },
            },
            scales: {
                x: {
                    ticks: {
                        color: '#4a5c4c',
                    },
                    grid: {
                        display: false,
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#4a5c4c',
                        precision: 0,
                    },
                },
            },
        },
    });
}

function destroyDashboardMovementChart() {
    const canvas = document.getElementById('dashboardMovementChart');

    if (!canvas) {
        return;
    }

    const existingChart = Chart.getChart(canvas);

    if (existingChart) {
        existingChart.destroy();
    }
}

document.addEventListener('DOMContentLoaded', renderDashboardMovementChart);
document.addEventListener('livewire:navigating', destroyDashboardMovementChart);
document.addEventListener('livewire:navigated', () => {
    requestAnimationFrame(renderDashboardMovementChart);
});
