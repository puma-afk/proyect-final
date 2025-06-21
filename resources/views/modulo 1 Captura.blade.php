<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deteccion de personas por Imagen</title>
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



    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Deteccion de Personas Por Imagen</h1>
            <p>Sistema para subir y procesar imagenes devolviendo las personas detectadas en total</p>
        </div>

        <div id="preview-container" style="text-align:center; margin-bottom:20px;">
            <img id="preview" src="" alt="Imagen seleccionada" style="max-width: 400px; max-height: 300px; display:none; border:2px solid #1e88e5;">
        </div>

        <div class="controls">
            <form action="{{ route('deteccionController') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="input-file-label" for="imagen">Seleccionar imagen</label>
                <input type="file" name="imagen" id="imagen" accept="image/*" required style="display:none;" onchange="mostrarVistaPrevia(event)">
                <button type="submit" class="btn">Subir Imagen</button>
            </form>

            <form action="{{ route('detectar.humanos') }}" method="POST">
                @csrf
                <button type="submit" class="btn">Detectar Personas</button>
            </form>

            <a class='btn' href="{{ route('perfil')}}">Atras</a>

        </div>

        @if(session()->has('cantidad'))
            <div class="status-display">
                Personas Detectadas: <span id="currentGesture"><strong>{{ session('cantidad') }}</strong></span>
            </div>
            <form action="{{ route('borrar.todo') }}" method="POST" style="margin-top: 1em;">
                @csrf
                <button type="submit" class="btn btn-danger">Borrar todo</button>
            </form>
        @endif

        @if(session()->has('imagenesProcesadas'))
            <div style="margin-top: 1em;">
                <h4>Imágenes procesadas:</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    @foreach(session('imagenesProcesadas') as $img)
                        <div style="border: 1px solid #ccc; padding: 5px; text-align: center;">
                            <p><strong>Nro:</strong> {{ $img['numero'] }}</p>
                            <img src="{{ Storage::url('detecciones/' . basename($img['imagen'])) }}" alt="Imagen procesada" style="max-width: 200px;">
                            <p><strong>Personas:</strong> {{ $img['personas_detectadas'] }}</p>
                        </div>
                    @endforeach
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
    @include('vistas-globales.vos-iu')
    @include('vistas-globales.vos-comandos') 
    <script src="{{ asset('voiceRecognition.js') }}"></script>


</body>

</html> 
