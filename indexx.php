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

    <h2 style="text-align: center;">Gráfica de Pastel: Formas Seleccionadas</h2>
    <canvas id="pastel"></canvas>

    <h2 style="text-align: center;">Polígono de Frecuencia: Frecuencia por Color</h2>
    <canvas id="poligono"></canvas>

    <h2 style="text-align: center;">Ojiva: Frecuencia Acumulada</h2>
    <canvas id="ojiva"></canvas>

    <div style="text-align: center;">
        <button onclick="window.print()">🖨️ Imprimir Reporte</button>
    </div>

<?php
// Leer el archivo .txt línea por línea
$contenido = file('datos.txt');
$formas = [];
$colores = [];
$intervalos = [];

$seccion = '';

foreach ($contenido as $linea) {
    $linea = trim($linea);
    if ($linea === '') continue;

    // Identificar secciones
    if (strpos($linea, '#') === 0) {
        if (stripos($linea, 'formas') !== false) $seccion = 'formas';
        elseif (stripos($linea, 'colores') !== false) $seccion = 'colores';
        elseif (stripos($linea, 'intervalos') !== false) $seccion = 'intervalos';
        continue;
    }

    // Separar clave y valor
    if (strpos($linea, ':') !== false) {
        list($clave, $valor) = explode(':', $linea);
        $clave = trim($clave);
        $valor = intval(trim($valor));

        if ($seccion === 'formas') $formas[$clave] = $valor;
        elseif ($seccion === 'colores') $colores[$clave] = $valor;
        elseif ($seccion === 'intervalos') $intervalos[$clave] = $valor;
    }
}

// Calcular frecuencia acumulada (ojiva)
$acumulada = [];
$suma = 0;
foreach ($intervalos as $clave => $valor) {
    $suma += $valor;
    $acumulada[$clave] = $suma;
}

// Convertir a JSON para JS
$formas_labels = json_encode(array_keys($formas));
$formas_valores = json_encode(array_values($formas));

$colores_labels = json_encode(array_keys($colores));
$colores_valores = json_encode(array_values($colores));

$ojiva_labels = json_encode(array_keys($acumulada));
$ojiva_valores = json_encode(array_values($acumulada));
?>

<script>
// Gráfica de pastel
new Chart(document.getElementById('pastel').getContext('2d'), {
    type: 'pie',
    data: {
        labels: <?= $formas_labels ?>,
        datasets: [{
            data: <?= $formas_valores ?>,
            backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0']
        }]
    },
    options: { responsive: false }
});

// Polígono de frecuencia
new Chart(document.getElementById('poligono').getContext('2d'), {
    type: 'line',
    data: {
        labels: <?= $colores_labels ?>,
        datasets: [{
            label: 'Frecuencia por Color',
            data: <?= $colores_valores ?>,
            borderColor: '#4bc0c0',
            backgroundColor: 'transparent',
            borderWidth: 2,
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

// Ojiva (frecuencia acumulada)
new Chart(document.getElementById('ojiva').getContext('2d'), {
    type: 'line',
    data: {
        labels: <?= $ojiva_labels ?>,
        datasets: [{
            label: 'Frecuencia Acumulada',
            data: <?= $ojiva_valores ?>,
            borderColor: '#ff6384',
            backgroundColor: 'transparent',
            borderWidth: 2,
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
