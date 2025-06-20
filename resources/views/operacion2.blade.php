<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Ayuda</title>
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
        
        .content {
            margin-bottom: 2rem;
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
        
        /* Estilos para la sección de comandos */
        .commands-section {
            margin-bottom: 1.5rem;
        }
        
        .commands-section h3 {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .commands-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .command-item {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }
        
        .command-item:hover {
            background-color: rgba(59, 130, 246, 0.2);
            transform: translateX(3px);
        }
        
        .command-icon {
            color: var(--primary);
            font-size: 1rem;
        }
        
        .command-text {
            font-size: 0.9rem;
        }
        
        .command-category {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <div class="info-container">
        <h2>
            <i class="fas fa-question-circle"></i> Comandos de Voz
        </h2>
        
        <div class="content">
            <p>Todos los comandos de voz que puedes utilizar en la pagina:</p>
            
            <div class="commands-section">
                <h3><i class="fas fa-map-marker-alt"></i> Navegación</h3>
                <div class="commands-list">
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-home"></i></span>
                        <span class="command-text">"ir a inicio", "volver", "inicio"</span>
                    </div>
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-database"></i></span>
                        <span class="command-text">"ir a datos", "ver datos", "mis datos"</span>
                    </div>
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-book"></i></span>
                        <span class="command-text">"modulo 1", "primera lección", "modulo uno"</span>
                    </div>
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-book-open"></i></span>
                        <span class="command-text">"modulo 2", "segunda lección", "modulo dos"</span>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-hands"></i> Gestos</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-hand-paper"></i></span>
                            <span class="command-text">"modulo dos gestos", "ir a gestos", "gestos"</span>
                        </div>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-chart-bar"></i> Estadísticas</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-chart-line"></i></span>
                            <span class="command-text">"ver estadisticas", "ir a estadisticas", "estadisticas"</span>
                        </div>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-user-circle"></i> Perfil</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-user"></i></span>
                            <span class="command-text">"ver mi perfil", "ir a perfil", "perfil"</span>
                        </div>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-microphone"></i> Control de Voz</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-microphone-slash"></i></span>
                            <span class="command-text">"detener", "desactivar micrófono", "silenciar"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-microphone"></i></span>
                            <span class="command-text">"activar", "empezar a escuchar", "escuchar"</span>
                        </div>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-sign-out-alt"></i> Sistema</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-power-off"></i></span>
                            <span class="command-text">"cerrar sesión", "salir del sistema", "logout"</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="help-text">la ayuda no es gratis</p>
        </div>
        
        <form method="GET" action="{{ route('perfil') }}">
            <button type="submit">
                <i class="fas fa-arrow-left"></i> Volver al perfil
            </button>
        </form>
    </div>
     @include('vistas-globales.vos-iu')
    @include('vistas-globales.vos-comandos') 
    <script src="{{ asset('voiceRecognition.js') }}"></script>
</body>
</html>