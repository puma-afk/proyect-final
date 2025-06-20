<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Usuario</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text: #e5e7eb;
            --bg: #111827;
            --card-bg: #1f2937;
            --border: #374151;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 1rem;
            line-height: 1.5;
        }
        
        .info-container {
            width: 100%;
            max-width: 500px;
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
        }
        
        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .user-info {
            list-style: none;
            margin-bottom: 2rem;
        }
        
        .user-info li {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
        }
        
        .user-info li:last-child {
            border-bottom: none;
        }
        
        .info-icon {
            width: 24px;
            color: var(--primary);
            margin-right: 1rem;
            text-align: center;
            font-size: 1rem;
        }
        
        .info-label {
            font-weight: 500;
            min-width: 120px;
            color: #9ca3af;
        }
        
        .info-value {
            margin-left: auto;
            font-weight: 400;
            text-align: right;
        }
        
        button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.875rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            gap: 0.5rem;
        }
        
        button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        @media (max-width: 480px) {
            .info-container {
                padding: 1.75rem;
            }
            
            .user-info li {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .info-label {
                min-width: 100%;
            }
            
            .info-value {
                margin-left: 0;
                text-align: left;
                width: 100%;
                padding-left: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="info-container">
        <h2>
            <i class="fas fa-id-card"></i> Perfil Completo
        </h2>
        
        <ul class="user-info">
            <li>
                <span class="info-icon"><i class="fas fa-signature"></i></span>
                <span class="info-label">Nombre de usuario:</span>
                <span class="info-value">{{ $username }}</span>
            </li>
            <li>
                <span class="info-icon"><i class="fas fa-at"></i></span>
                <span class="info-label">Correo electrónico:</span>
                <span class="info-value">{{ $email }}</span>
            </li>
            <li>
                <span class="info-icon"><i class="fas fa-mobile-alt"></i></span>
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $telefono }}</span>
            </li>
            <li>
                <span class="info-icon"><i class="fas fa-circle"></i></span>
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    <span style="color: {{ $estado == 'Activo' ? '#10b981' : '#ef4444' }};">
                        {{ $estado }}
                    </span>
                </span>
            </li>
            <li>
                <span class="info-icon"><i class="fas fa-calendar-alt"></i></span>
                <span class="info-label">Último acceso:</span>
                <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
            </li>
        </ul>
        
           <form method="GET" action="{{ route('perfil') }}">
            <button type="submit">Volver al perfil</button>
           </form>
    </div>
     @include('vistas-globales.vos-iu')
     @include('vistas-globales.vos-comandos') 
     <script src="{{ asset('voiceRecognition.js') }}"></script>
</body>
</html>
