<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda - Comandos de Voz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text: #e5e7eb;
            --bg: #111827;
            --card-bg: #1f2937;
            --border: #374151;
            --success: #10b981;
            --success-hover: #059669;
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
            padding: 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
        }
        
        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        
        .content {
            margin-bottom: 1.5rem;
        }
        
        .intro-text {
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 0.95rem;
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
            margin-bottom: 1rem;
        }
        
        button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        #readBtn {
            background-color: var(--success);
        }
        
        #readBtn:hover:not(:disabled) {
            background-color: var(--success-hover);
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
            min-width: 20px;
            text-align: center;
        }
        
        .command-text {
            font-size: 0.9rem;
        }
        
        .command-category {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
        
        .help-text {
            text-align: center;
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 1.5rem;
            font-style: italic;
        }
        
        @media (max-width: 480px) {
            .info-container {
                padding: 1.5rem;
            }
            
            h2 {
                font-size: 1.3rem;
            }
            
            .command-text {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="info-container">
        <h2>
            <i class="fas fa-question-circle"></i> Comandos de Voz
        </h2>
        
        <button id="readBtn">
            <i class="fas fa-volume-up"></i> Leer comandos
        </button>
        
        <div class="content">
            <p class="intro-text">Todos los comandos de voz que puedes utilizar en la página:</p>
            
            <div class="commands-section">
                <h3><i class="fas fa-map-marker-alt"></i> Navegación</h3>
                <div class="commands-list">
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-home"></i></span>
                        <span class="command-text">"volver a inicio", "volver", "inicio"</span>
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
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-chart-line"></i></span>
                        <span class="command-text">"modulo 4", "modulo cuatro"</span>
                    </div>
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-question-circle"></i></span>
                        <span class="command-text">"ayuda", "informacion de ayuda", "mostrar ayuda"</span>
                    </div>
                    <div class="command-item">
                        <span class="command-icon"><i class="fas fa-user"></i></span>
                        <span class="command-text">"ver mi perfil", "ir a perfil", "perfil"</span>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-hands"></i> Gestos</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-hand-paper"></i></span>
                            <span class="command-text">"control de gestos", "ir a gestos", "gestos"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-play"></i></span>
                            <span class="command-text">"iniciar deteccion", "dectectar gestos"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-stop"></i></span>
                            <span class="command-text">"detener camara", "detener gestos"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-microphone"></i></span>
                            <span class="command-text">"volver a control de vos", "volver a vos"</span>
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
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-sign-out-alt"></i></span>
                            <span class="command-text">"cerrar sesión", "salir del sistema", "logout"</span>
                        </div>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-mouse-pointer"></i> Acciones Generales</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-arrow-down"></i></span>
                            <span class="command-text">"bajar", "desplazar abajo", "scroll abajo"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-arrow-up"></i></span>
                            <span class="command-text">"subir", "desplazar arriba", "scroll arriba"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-list"></i></span>
                            <span class="command-text">"comandos"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-check"></i></span>
                            <span class="command-text">"probar comandos"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-cog"></i></span>
                            <span class="command-text">"configuracion"</span>
                        </div>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-image"></i> Manejo de Imágenes</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-mouse-pointer"></i></span>
                            <span class="command-text">"seleccionar imagen", "seleccionar"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-upload"></i></span>
                            <span class="command-text">"subir imagen", "cargar imagen"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-user-friends"></i></span>
                            <span class="command-text">"detectar personas", "detectar"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-trash"></i></span>
                            <span class="command-text">"borrar todo", "borrar"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-arrow-right"></i></span>
                            <span class="command-text">"siguiente imagen", "siguiente"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-arrow-left"></i></span>
                            <span class="command-text">"anterior imagen", "anterior"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-arrow-left"></i></span>
                            <span class="command-text">"atras", "salir de modulo 1"</span>
                        </div>
                    </div>
                </div>
                
                <div class="command-category">
                    <h3><i class="fas fa-cube"></i> Detección de Objetos</h3>
                    <div class="commands-list">
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-mouse-pointer"></i></span>
                            <span class="command-text">"selecionar imagen objeto", "selecionar objeto"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-search"></i></span>
                            <span class="command-text">"detectar objeto", "detectar objetos"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-trash"></i></span>
                            <span class="command-text">"borrar imagen", "borrar"</span>
                        </div>
                        <div class="command-item">
                            <span class="command-icon"><i class="fas fa-arrow-left"></i></span>
                            <span class="command-text">"atras", "volver a inicio"</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="help-text">gracias</p>
        </div>
        
        <form method="GET" action="{{ route('perfil') }}">
            <button type="submit">
                <i class="fas fa-arrow-left"></i> Volver al perfil
            </button>
        </form>
    </div>
    

    <script>
        document.getElementById('readBtn').addEventListener('click', function() {
            // Verificar compatibilidad con la API de síntesis de voz
            if (!('speechSynthesis' in window)) {
                alert('Lo siento, tu navegador no soporta la síntesis de voz.');
                return;
            }
            
            // Obtener todos los textos de los comandos
            const categories = document.querySelectorAll('.commands-section h3');
            const commandLists = document.querySelectorAll('.commands-list');
            
            let fullText = "Comandos de voz disponibles. ";
            
            categories.forEach((category, index) => {
                fullText += `Categoría: ${category.textContent}. `;
                
                const commands = commandLists[index].querySelectorAll('.command-text');
                commands.forEach((cmd, i) => {
                    fullText += `Comando ${i + 1}: ${cmd.textContent}. `;
                });
            });
            
            fullText += "Fin de los comandos disponibles.";
            
            // Crear y configurar el utterance
            const utterance = new SpeechSynthesisUtterance(fullText);
            
            // Intentar encontrar una voz en español
            const voices = window.speechSynthesis.getVoices();
            const spanishVoice = voices.find(voice => 
                voice.lang.includes('es') || 
                voice.lang.includes('ES') ||
                voice.name.includes('Spanish') ||
                voice.name.includes('español')
            );
            
            if (spanishVoice) {
                utterance.voice = spanishVoice;
                utterance.lang = 'es-ES';
            }
            
            utterance.rate = 0.9; 
            utterance.pitch = 1;  
            
           
            const readBtn = document.getElementById('readBtn');
            readBtn.innerHTML = '<i class="fas fa-volume-up"></i> Leyendo...';
            readBtn.disabled = true;
            
            utterance.onend = function() {
                readBtn.innerHTML = '<i class="fas fa-volume-up"></i> Leer comandos';
                readBtn.disabled = false;
            };
            
            utterance.onerror = function(event) {
                console.error('Error en síntesis de voz:', event);
                readBtn.innerHTML = '<i class="fas fa-volume-up"></i> Leer comandos';
                readBtn.disabled = false;
                alert('Error al leer los comandos');
            };
            
        
            window.speechSynthesis.speak(utterance);
        });
    </script>
       
    @include('vistas-globales.vos-iu')
    @include('vistas-globales.vos-comandos') 
    <script src="{{ asset('voiceRecognition.js') }}"></script>
    
</body>
</html>