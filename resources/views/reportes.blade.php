<!DOCTYPE html>
<html>
<head>
    <title>Reportes Estadísticos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            width: 500px !important;
            height: 500px !important;
            display: block;
            margin: 40px auto;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Gráfica de Pastel: Formas</h2>
    <canvas id="pastel"></canvas>

    <h2 style="text-align: center;">Polígono de Frecuencia: Colores</h2>
    <canvas id="poligono"></canvas>

    <h2 style="text-align: center;">Ojiva: Frecuencia Acumulada</h2>
    <canvas id="ojiva"></canvas>

    <div style="text-align: center;">
        <button onclick="window.print()">🖨️ Imprimir</button>
    </div>

<script>
const formasLabels = @json($formasLabels);
const formasValores = @json($formasValores);

const coloresLabels = @json($coloresLabels);
const coloresValores = @json($coloresValores);

const ojivaLabels = @json($ojivaLabels);
const ojivaValores = @json($ojivaValores);

new Chart(document.getElementById('pastel').getContext('2d'), {
    type: 'pie',
    data: {
        labels: formasLabels,
        datasets: [{
            data: formasValores,
            backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0']
        }]
    },
    options: { responsive: false }
});

new Chart(document.getElementById('poligono').getContext('2d'), {
    type: 'line',
    data: {
        labels: coloresLabels,
        datasets: [{
            label: 'Frecuencia por Color',
            data: coloresValores,
            borderColor: '#4bc0c0',
            backgroundColor: 'transparent',
            pointBackgroundColor: '#4bc0c0',
            tension: 0.3
        }]
    },
    options: {
        responsive: false,
        scales: {
            y: { beginAtZero: true }
        }
    }
});

new Chart(document.getElementById('ojiva').getContext('2d'), {
    type: 'line',
    data: {
        labels: ojivaLabels,
        datasets: [{
            label: 'Frecuencia Acumulada',
            data: ojivaValores,
            borderColor: '#ff6384',
            backgroundColor: 'transparent',
            pointBackgroundColor: '#ff6384',
            tension: 0.3
        }]
    },
    options: {
        responsive: false,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
