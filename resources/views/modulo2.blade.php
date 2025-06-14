<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control por Voz Minimalista</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #ffffff;
            min-height: 100vh;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px 0;
            border-bottom: 2px solid #1e88e5;
        }
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 300;
            letter-spacing: 1px;
        }
        .header p {
            font-size: 1rem;
            opacity: 0.8;
            font-weight: 300;
        }
        .navigation {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .nav-btn {
            background: transparent;
            border: 1px solid #1e88e5;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .nav-btn:hover {
            background: rgba(30, 136, 229, 0.1);
        }
        .nav-btn.active {
            background: #1e88e5;
        }
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }
        .control-panel {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 25px;
            border-left: 4px solid #1e88e5;
        }
        .control-panel h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 400;
            color: #1e88e5;
        }
        .voice-controls {
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }
        .btn {
            background: transparent;
            border: 1px solid #1e88e5;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 400;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 250px;
        }
        .btn:hover {
            background: rgba(30, 136, 229, 0.1);
        }
        .btn.active {
            background: #1e88e5;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(30, 136, 229, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(30, 136, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(30, 136, 229, 0); }
        }
        .text-input-section {
            margin-top: 25px;
        }
        .text-input-section label {
            display: block;
            margin-bottom: 10px;
            font-weight: 400;
            color: #bbbbbb;
        }
        .text-input-section textarea {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #333;
            background: #252525;
            color: white;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
            transition: border 0.3s;
        }
        .text-input-section textarea:focus {
            outline: none;
            border-color: #1e88e5;
        }
        .commands-panel {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 25px;
            border-left: 4px solid #1e88e5;
        }
        .commands-panel h3 {
            margin-bottom: 20px;
            color: #1e88e5;
            font-size: 1.3rem;
            font-weight: 400;
        }
        .command-list {
            display: grid;
            gap: 10px;
        }
        .command-item {
            background: #252525;
            padding: 15px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 3px solid transparent;
        }
        .command-item:hover {
            border-left: 3px solid #1e88e5;
            background: #2a2a2a;
        }
        .command-phrase {
            font-weight: 500;
            color: #ffffff;
        }
        .command-description {
            font-size: 13px;
            color: #bbbbbb;
        }
        .status-panel {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
            grid-column: 1 / -1;
            border-left: 4px solid #1e88e5;
        }
        .status-panel h3 {
            margin-bottom: 20px;
            color: #1e88e5;
            font-size: 1.5rem;
            font-weight: 400;
        }
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .status-item {
            background: #252525;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .status-label {
            font-size: 13px;
            color: #bbbbbb;
            margin-bottom: 8px;
        }
        .status-value {
            font-weight: 500;
            color: #1e88e5;
            font-size: 16px;
        }
        .logs {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 20px;
            height: 250px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin-top: 30px;
            border-left: 4px solid #1e88e5;
        }
        .logs h3 {
            margin-bottom: 15px;
            color: #1e88e5;
            font-family: 'Segoe UI', sans-serif;
            font-weight: 400;
        }
        .log-entry {
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #333;
        }
        .log-voice {
            color: #64b5f6;
        }
        .log-system {
            color: #ff5252;
        }
        .log-success {
            color: #69f0ae;
        }
        .log-warning {
            color: #ffd740;
        }
        .error-message {
            background: rgba(255, 82, 82, 0.1);
            border: 1px solid #ff5252;
            color: #ff5252;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            display: none;
            text-align: center;
            font-weight: 500;
        }
        .microphone-icon {
            font-size: 3.5rem;
            margin: 20px 0;
            text-align: center;
            transition: all 0.3s ease;
            color: #333;
        }
        .microphone-icon.active {
            color: #1e88e5;
            animation: micPulse 2s infinite;
        }
        .microphone-icon.processing {
            color: #ba68c8;
            animation: none;
        }
        @keyframes micPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            .header h1 {
                font-size: 2rem;
            }
            .navigation {
                flex-direction: column;
                align-items: center;
            }
            .status-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Control de Voz</h1>
            <p>Interfaz minimalista con procesamiento secuencial</p>
        </div>
        <div class="navigation">
            <div class="nav-btn active">Control de voz</div>
            <a href="#" class="nav-btn" id="gestureNavBtn">Control de gestos</a>
        </div>
        <div id="errorContainer" class="error-message"></div>
        <div class="main-content">
            <div class="control-panel">
                <h2>Reconocimiento de Voz</h2>
                <div class="microphone-icon" id="micIcon">🎤</div>
                <div class="voice-controls">
                    <button id="startVoiceBtn" class="btn">Iniciar Reconocimiento</button>
                    <button id="stopVoiceBtn" class="btn">Detener Reconocimiento</button>
                    <button id="cancelBtn" class="btn" style="display: none;">Cancelar Comando</button>
                    <button id="speakBtn" class="btn">Hablar Texto</button>
                </div>
                <div class="text-input-section">
                    <label for="textToSpeak">Texto a Sintetizar:</label>
                    <textarea id="textToSpeak" placeholder="Escribe aquí el texto que quieres que el sistema diga...">¡Hola! Soy tu asistente virtual mejorado. Ahora proceso los comandos uno a uno sin solapamientos.</textarea>
                </div>
            </div>
            <div class="control-panel">
                <div class="commands-panel">
                    <h3>Comandos Disponibles</h3>
                    <div class="command-list">
                        <div class="command-item" onclick="testCommand('hola')">
                            <div>
                                <div class="command-phrase">"Hola"</div>
                                <div class="command-description">Saludos iniciales</div>
                            </div>
                        </div>
                        <div class="command-item" onclick="testCommand('ayuda')">
                            <div>
                                <div class="command-phrase">"Ayuda"</div>
                                <div class="command-description">Información de comandos</div>
                            </div>
                        </div>
                        <div class="command-item" onclick="testCommand('adiós')">
                            <div>
                                <div class="command-phrase">"Adiós"</div>
                                <div class="command-description">Despedida</div>
                            </div>
                        </div>
                        <div class="command-item" onclick="testCommand('página principal')">
                            <div>
                                <div class="command-phrase">"Página principal"</div>
                                <div class="command-description">Ir al inicio</div>
                            </div>
                        </div>
                        <div class="command-item" onclick="testCommand('atrás')">
                            <div>
                                <div class="command-phrase">"Atrás"</div>
                                <div class="command-description">Navegación hacia atrás</div>
                            </div>
                        </div>
                        <div class="command-item" onclick="testCommand('ir a gestos')">
                            <div>
                                <div class="command-phrase">"Ir a gestos"</div>
                                <div class="command-description">Cambiar el control por gestos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="status-panel">
            <h3>Estado del Sistema</h3>
            <div class="status-grid">
                <div class="status-item">
                    <div class="status-label">Estado de Voz</div>
                    <div class="status-value" id="voiceStatus">Inactivo</div>
                </div>
                <div class="status-item">
                    <div class="status-label">Comandos Procesados</div>
                    <div class="status-value" id="commandCount">0</div>
                </div>
                <div class="status-item">
                    <div class="status-label">Último Comando</div>
                    <div class="status-value" id="lastCommand">Ninguno</div>
                </div>
                <div class="status-item">
                    <div class="status-label">Procesando</div>
                    <div class="status-value" id="processingStatus">No</div>
                </div>
            </div>
        </div>
        <div class="logs" id="logContainer">
            <h3>Registro de Actividad</h3>
            <div class="log-entry log-system">[Sistema inicializado]</div>
        </div>
    </div>

    <script>
        // Variables globales mejoradas
        let recognition = null;
        let synthesis = window.speechSynthesis;
        let isVoiceActive = false;
        let isProcessingCommand = false;
        let commandCount = 0;
        let recognitionRestartTimer = null;
        let startTime = 0;

        // Elementos del DOM
        const startVoiceBtn = document.getElementById('startVoiceBtn');
        const stopVoiceBtn = document.getElementById('stopVoiceBtn');
        const speakBtn = document.getElementById('speakBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const textToSpeak = document.getElementById('textToSpeak');
        const logContainer = document.getElementById('logContainer');
        const errorContainer = document.getElementById('errorContainer');
        const micIcon = document.getElementById('micIcon');
        const gestureNavBtn = document.getElementById('gestureNavBtn');
        const voiceStatus = document.getElementById('voiceStatus');
        const processingStatus = document.getElementById('processingStatus');

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            checkBrowserSupport();
            initializeSpeechRecognition();
            setupEventListeners();
            log('Sistema de reconocimiento de voz inicializado correctamente', 'system');
        });

        // Verificar soporte del navegador
        function checkBrowserSupport() {
            const errors = [];
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                errors.push('Reconocimiento de voz no soportado');
            }
            if (!window.speechSynthesis) {
                errors.push('Síntesis de voz no soportada');
            }
            if (errors.length > 0) {
                showError('Funciones no soportadas: ' + errors.join(', '));
                document.querySelectorAll('.voice-controls button').forEach(btn => {
                    btn.disabled = true;
                });
            }
        }

        // Configuración del reconocimiento de voz mejorada
        function initializeSpeechRecognition() {
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();
                
                recognition.continuous = true;
                recognition.interimResults = false;
                recognition.lang = 'es-ES';
                recognition.maxAlternatives = 1;

                recognition.onstart = function() {
                    isVoiceActive = true;
                    updateStatus('voiceStatus', 'Escuchando');
                    startVoiceBtn.classList.add('active');
                    micIcon.classList.add('active');
                    log('Reconocimiento de voz iniciado - Escuchando comandos...', 'voice');
                };

                recognition.onresult = function(event) {
                    const result = event.results[event.resultIndex];
                    if (result.isFinal) {
                        const transcript = result[0].transcript.trim();
                        if (transcript) {
                            processVoiceCommand(transcript);
                        }
                    }
                };

                recognition.onerror = function(event) {
                    let errorMsg = 'Error: ';
                    switch(event.error) {
                        case 'no-speech':
                            errorMsg += 'No se detectó voz';
                            break;
                        case 'audio-capture':
                            errorMsg += 'No se pudo acceder al micrófono';
                            break;
                        case 'not-allowed':
                            if (event.timeStamp - startTime < 100) {
                                errorMsg += 'Permisos denegados (bloqueado por el navegador)';
                            } else {
                                errorMsg += 'El usuario denegó los permisos';
                            }
                            break;
                        default:
                            errorMsg += event.error;
                    }
                    
                    showError(errorMsg);
                    log(`Error de voz: ${errorMsg}`, 'system');
                    
                    if (event.error !== 'not-allowed') {
                        setTimeout(() => {
                            if (!isVoiceActive) return;
                            try {
                                recognition.start();
                            } catch(e) {
                                log(`Error al reintentar: ${e.message}`, 'system');
                            }
                        }, 1000);
                    }
                };

                recognition.onend = function() {
                    if (isVoiceActive && !isProcessingCommand) {
                        restartRecognition();
                    }
                };
            }
        }

        // Procesamiento mejorado de comandos de voz
        function processVoiceCommand(command) {
            if (isProcessingCommand) {
                log('Ignorando comando: ya hay uno en proceso', 'warning');
                return;
            }

            command = command.toLowerCase();
            commandCount++;
            updateStatus('commandCount', commandCount);
            log(`Comando recibido: "${command}"`, 'voice');
            updateStatus('lastCommand', command);

            // Detener el reconocimiento temporalmente
            recognition.stop();
            isProcessingCommand = true;
            updateStatus('voiceStatus', 'Procesando');
            updateStatus('processingStatus', 'Sí');
            updateProcessingUI(true);
            cancelBtn.style.display = 'inline-block';

            // Ejecutar la acción del comando
            executeCommandAction(command).finally(() => {
                isProcessingCommand = false;
                updateStatus('processingStatus', 'No');
                updateProcessingUI(false);
                cancelBtn.style.display = 'none';
                
                // Reiniciar reconocimiento si aún está activo
                if (isVoiceActive) {
                    restartRecognition();
                }
            });
        }

        // Ejecución de acciones con async/await
        async function executeCommandAction(command) {
            try {
                if (command.includes('hola') || command.includes('hello')) {
                    await speakAndWait('¡Hola! ¿En qué puedo ayudarte hoy?');
                    log('Saludo completado', 'success');
                }
                else if (command.includes('adiós') || command.includes('hasta luego')) {
                    await speakAndWait('¡Hasta luego! Que tengas un excelente día.');
                    log('Despedida completada', 'success');
                }
                else if (command.includes('ayuda')) {
                    await speakAndWait('Puedes usar comandos como: hola, adiós, página principal, atrás, o ir a gestos.');
                    log('Ayuda mostrada', 'success');
                }
                else if (command.includes('página principal') || command.includes('inicio')) {
                    await speakAndWait('Navegando a la página principal');
                    log('Navegando a inicio', 'success');
                    setTimeout(() => {
                        window.location.href = '#';
                    }, 1000);
                }
                else if (command.includes('atrás') || command.includes('volver')) {
                    await speakAndWait('Navegando hacia atrás');
                    log('Navegando hacia atrás', 'success');
                    setTimeout(() => {
                        window.history.back();
                    }, 1000);
                }
                else if (command.includes('ir a gestos') || command.includes('control por gestos') || command.includes('gestos')) {
                    await speakAndWait('Cambiando al control por gestos');
                    log('Cambiando a control por gestos', 'success');
                    setTimeout(() => {
                        showGestureControl();
                    }, 1000);
                }
                else if (command.includes('detener') || command.includes('parar')) {
                    if (isVoiceActive) {
                        recognition.stop();
                        await speakAndWait('Reconocimiento de voz detenido');
                        log('Reconocimiento detenido por comando', 'success');
                    }
                }
                else {
                    await speakAndWait('Comando recibido: ' + command + '. Si necesitas ayuda, di "ayuda" para ver los comandos disponibles.');
                    log('Comando procesado: ' + command, 'voice');
                }
            } catch (error) {
                log(`Error ejecutando comando: ${error.message}`, 'system');
                await speakAndWait('Hubo un error al procesar el comando');
            }
        }

        // Hablar y esperar a que termine
        function speakAndWait(text) {
            return new Promise((resolve) => {
                if (synthesis && synthesis.speak) {
                    synthesis.cancel();
                    
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'es-ES';
                    utterance.rate = 0.9;
                    utterance.pitch = 1;
                    utterance.volume = 0.8;
                    
                    utterance.onend = function() {
                        resolve();
                    };
                    
                    utterance.onerror = function() {
                        resolve();
                    };
                    
                    synthesis.speak(utterance);
                    log(`Sintetizando: "${text}"`, 'voice');
                } else {
                    resolve();
                }
            });
        }

        // Reinicio seguro del reconocimiento
        function restartRecognition() {
            if (recognitionRestartTimer) {
                clearTimeout(recognitionRestartTimer);
            }

            recognitionRestartTimer = setTimeout(() => {
                try {
                    if (!isVoiceActive || isProcessingCommand) return;
                    
                    recognition.start();
                    updateStatus('voiceStatus', 'Escuchando');
                    log('Reconocimiento reiniciado', 'system');
                } catch (error) {
                    log(`Error al reiniciar: ${error.message}`, 'system');
                    setTimeout(restartRecognition, 1000);
                }
            }, 500);
        }

        // Configuración de event listeners
        function setupEventListeners() {
            startVoiceBtn.addEventListener('click', async () => {
                try {
                    // Solicitar permisos primero
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    stream.getTracks().forEach(track => track.stop());
                    startTime = performance.now();
                    
                    if (recognition && !isVoiceActive) {
                        recognition.start();
                        log('Solicitando permisos de micrófono...', 'system');
                    }
                } catch (error) {
                    showError('Debes permitir el acceso al micrófono');
                    log(`Permisos denegados: ${error.message}`, 'system');
                }
            });

            stopVoiceBtn.addEventListener('click', () => {
                if (recognition && isVoiceActive) {
                    recognition.stop();
                    isVoiceActive = false;
                    updateStatus('voiceStatus', 'Inactivo');
                    startVoiceBtn.classList.remove('active');
                    micIcon.classList.remove('active');
                    log('Reconocimiento detenido manualmente', 'system');
                }
            });

            cancelBtn.addEventListener('click', () => {
                if (isProcessingCommand) {
                    synthesis.cancel();
                    isProcessingCommand = false;
                    updateStatus('processingStatus', 'No');
                    updateProcessingUI(false);
                    cancelBtn.style.display = 'none';
                    log('Comando cancelado manualmente', 'system');
                    
                    if (isVoiceActive) {
                        restartRecognition();
                    }
                }
            });

            speakBtn.addEventListener('click', () => {
                const text = textToSpeak.value.trim();
                if (text) {
                    speakAndWait(text);
                } else {
                    showError('Ingresa texto para sintetizar');
                }
            });

            gestureNavBtn.addEventListener('click', (e) => {
                e.preventDefault();
                showGestureControl();
            });

            // Atajos de teclado
            document.addEventListener('keydown', (e) => {
                if (e.ctrlKey && e.key === ' ') {
                    e.preventDefault();
                    if (!isVoiceActive) {
                        startVoiceBtn.click();
                    } else {
                        stopVoiceBtn.click();
                    }
                }
                if (e.ctrlKey && e.key === 'Enter') {
                    e.preventDefault();
                    speakBtn.click();
                }
            });
        }

        // Actualizar UI de procesamiento
        function updateProcessingUI(isProcessing) {
            if (isProcessing) {
                micIcon.classList.remove('active');
                micIcon.classList.add('processing');
            } else {
                micIcon.classList.remove('processing');
                if (isVoiceActive) {
                    micIcon.classList.add('active');
                }
            }
        }

        // Probar comando desde la interfaz
        function testCommand(command) {
            log('Comando de prueba: "' + command + '"', 'system');
            processVoiceCommand(command);
        }

        // Mostrar control por gestos (simulado)
        function showGestureControl() {
            alert('Redirigiendo al Control por Gestos...\n\nEn una implementación real, esto cargaría la página de gestos.');
            log('Redirección a control por gestos', 'system');
        }

        // Utilidades
        function updateStatus(elementId, value) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = value;
            }
        }

        function log(message, type = 'system') {
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.className = `log-entry log-${type}`;
            logEntry.textContent = `[${timestamp}] ${message}`;
            logContainer.appendChild(logEntry);
            logContainer.scrollTop = logContainer.scrollHeight;

            // Limitar registros
            const logs = logContainer.children;
            if (logs.length > 50) {
                logContainer.removeChild(logs[1]);
            }
        }

        function showError(message) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            log(`${message}`, 'system');
            
            setTimeout(() => {
                errorContainer.style.display = 'none';
            }, 5000);
        }

        // Limpieza al cerrar la página
        window.addEventListener('beforeunload', () => {
            if (isVoiceActive && recognition) {
                recognition.stop();
            }
            if (synthesis && synthesis.speaking) {
                synthesis.cancel();
            }
        });
    </script>
</body>
</html>