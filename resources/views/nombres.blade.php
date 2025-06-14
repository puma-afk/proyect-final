<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado - Plantilla Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dark-bg: #121212;
            --darker-bg: #0a0a0a;
            --card-bg: #1e1e1e;
            --text-primary: #ffffff; /* Texto blanco puro */
            --text-secondary: #f0f0f0; /* Gris muy claro */
            --accent-blue: #4285f4;
            --accent-blue-light: #5d9bff;
            --border-color: #444;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary); /* Texto principal blanco */
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            border-top: 3px solid var(--accent-blue);
        }
        
        h1 {
            color: var(--text-primary); /* Título en blanco */
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
            font-weight: 500;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--accent-blue);
        }
        
        .table {
            width: 100%;
            margin-top: 20px;
            color: var(--text-primary); /* Texto de tabla blanco */
            border-color: var(--border-color);
        }
        
        .table th {
            background-color: var(--darker-bg);
            border-color: var(--border-color) !important;
            color: var(--accent-blue-light); /* Encabezados en azul claro */
            font-weight: 500;
            padding: 12px 8px;
        }
        
        .table td {
            background-color: var(--card-bg);
            border-color: var(--border-color) !important;
            color: var(--text-primary); /* Texto de celdas en blanco */
            padding: 10px 8px;
        }
        
        .table tr:hover td {
            background-color: rgba(66, 133, 244, 0.15);
        }
        
        .blue-stripe {
            height: 5px;
            background: linear-gradient(90deg, var(--dark-bg), var(--accent-blue), var(--dark-bg));
            margin: 30px 0;
            border-radius: 5px;
        }
        
        .btn-back {
            background-color: var(--accent-blue);
            color: white !important; /* Texto del botón en blanco */
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s;
            font-weight: 500;
            text-decoration: none;
        }
        
        .btn-back:hover {
            background-color: var(--accent-blue-light);
            color: white !important;
            transform: translateY(-2px);
        }
        
        .btn-back i {
            margin-right: 8px;
            color: white;
        }

        /* Asegurar que todos los textos sean visibles */
        a, p, span, div, td, th {
            color: var(--text-primary) !important;
        }

        /* Mejor contraste para los números */
        .table th:first-child {
            color: var(--text-secondary) !important;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $archivo = file_get_contents('nombres.txt');
            $a_archivo = explode(",", $archivo);
            $cont = 1;
        @endphp  

        <a href="#" class="btn-back" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        
        <h1>LISTADO</h1>
        
        <div class="blue-stripe"></div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nro.</th>
                    <th>Nombres</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($a_archivo as $l)
                    <tr>
                        <th>{{ $cont }}</th>
                        <td>{{ $l }}</td>
                    </tr>
                    @php $cont++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>