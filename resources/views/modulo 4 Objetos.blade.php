<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deteccion de personas por Imagen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: white;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container{
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header{
            text-align: center;
            margin-bottom: 40px;
            padding: 20px 0;
            border-bottom: 2px solid #1e88e5;
        }

        .header h1{
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .controls{
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn{
            background: transparent;
            border: 1px solid #1e88e5;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            transition: all 0.3s ease;
            min-width: 180px;
        }

        .btn:hover{
            background: rgba(30, 136, 229, 0.1);
        }

        .btn.active{
            background: #1e88e5;
            animation: pulse 2s infinite;
        }

        @keyframes pulse{
            0% { box-shadow: 0 0 0 0 rgba(30, 136, 229, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(30, 136, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(30, 136, 229, 0); }
        }

        .input-file-label{
            background-color: #1e88e5;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .input-group input[type="search"] {
            background-color: #1e1e1e;
            border: 1px solid #1e88e5;
            color: white;
            padding: 8px 12px;
            border-radius: 4px 0 0 4px;
        }

        .input-group button {
            background-color: #1e88e5;
            border: 1px solid #1e88e5;
            color: white;
            padding: 8px 12px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }

        .input-group button:hover {
            background-color: #1565c0;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Deteccion de Objetos dados Por El Usuario</h1>
            <p>Sistema para subir y procesar imagenes devolviendo los objetos disponibles en la libreria</p>
        </div>

        <div id="preview-container" style="text-align:center; margin-bottom:20px;">
            <img id="preview" src="" alt="Imagen seleccionada" style="max-width: 400px; max-height: 300px; display:none; border:2px solid #1e88e5;">
        </div>

        <div class="controls">
            <form id="uploadForm" action="{{ route('detectar.objeto') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label id="fileLabel" class="input-file-label" for="imagen">Seleccionar imagen</label>
                <input type="file" name="imagen" id="imagen" accept="image/*" required style="display:none;" onchange="mostrarVistaPrevia(event)">

                <div class="input-group input-group-sm mb-3" style="margin-top:10px;">
                    <input class="form-control" type="search" name="objeto" id="searchInput" placeholder="Nombre del objeto a detectar" required aria-label="Search">
                    <button class="btn btn-outline-light" type="submit" id="detectButton">
                        <i class="fas fa-search"></i> Detectar Objeto
                    </button>
                </div>
            </form>
        </div>


        @if(session()->has('objetos_detectados'))
            <div id="statusDisplay" class="status-display">
                Objetos Detectados: <span id="detectionCount"><strong>{{ session('objetos_detectados') }}</strong></span>
            </div>
            <form id="deleteForm" action="{{ route('borrar.objetos') }}" method="POST" style="margin-top: 1em;">
                @csrf
                <button type="submit" id="deleteBtn" class="btn btn-danger">Borrar</button>
            </form>
        @endif


       @if(session()->has('ruta_procesada'))
            <div style="margin-top: 1em;">
                <h4>Imagen procesada:</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <div style="border: 1px solid #ccc; padding: 5px; text-align: center;">
                        <img src="{{ Storage::url(session('ruta_procesada')) }}" alt="Imagen procesada" style="max-width: 200px;">
                    </div>
                </div>
            </div>
        @endif



        @if ($errors->any())
            <div class="alert alert-danger" style="padding: 10px; border: 1px solid red;">
                <ul style="list-style: none; padding-left: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    <script>
        function mostrarVistaPrevia(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'inline';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>