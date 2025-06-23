<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Comandos de Voz</title>
    <style>
        :root {
            --primary-color: #1a73e8;
            --primary-dark: #0d47a1;
            --background-dark: #121212;
            --card-dark: #1e1e1e;
            --text-light: #f0f0f0;
            --text-muted: #aaaaaa;
            --success-color: #4caf50;
            --error-color: #f44336;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-dark);
            color: var(--text-light);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 2px solid var(--primary-color);
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
            border: 1px solid var(--primary-color);
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
            background: var(--primary-color);
        }
        
        .admin-panels {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .admin-panels {
                grid-template-columns: 1fr;
            }
        }
        
        .panel {
            background: var(--card-dark);
            border-radius: 8px;
            padding: 25px;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .panel h2 {
            margin-bottom: 20px;
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .panel h2 i {
            font-size: 1.3rem;
        }
        
        .command-controls {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .control-group {
            margin-bottom: 20px;
        }
        
        .btn {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 400;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn:disabled {
            background: #333;
            color: var(--text-muted);
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-secondary {
            background: #333;
        }
        
        .btn-secondary:hover {
            background: #444;
        }
        
        .btn-success {
            background: var(--success-color);
        }
        
        .btn-success:hover {
            background: #3d8b40;
        }
        
        .btn-danger {
            background: var(--error-color);
        }
        
        .btn-danger:hover {
            background: #d32f2f;
        }
        
        .btn.active {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(30, 136, 229, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(30, 136, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(30, 136, 229, 0); }
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-light);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #333;
            background: #252525;
            color: white;
            font-size: 14px;
            transition: border 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .command-list {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
            border-radius: 6px;
            background: #252525;
            padding: 10px;
        }
        
        .command-item {
            padding: 12px 15px;
            margin-bottom: 8px;
            background: #2a2a2a;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .command-item:hover {
            background: #333;
        }
        
        .command-phrase {
            font-weight: 500;
            color: var(--text-light);
        }
        
        .command-action {
            font-size: 13px;
            color: var(--primary-color);
            font-family: 'Courier New', monospace;
        }
        
        .command-actions {
            display: flex;
            gap: 8px;
        }
        
        .command-btn {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .command-btn.test {
            background: var(--primary-color);
            color: white;
        }
        
        .command-btn.edit {
            background: #ff9800;
            color: white;
        }
        
        .command-btn.delete {
            background: var(--error-color);
            color: white;
        }
        
        .status-panel {
            background: var(--card-dark);
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
            border-left: 4px solid var(--primary-color);
        }
        
        .status-panel h3 {
            margin-bottom: 20px;
            color: var(--primary-color);
            font-size: 1.3rem;
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
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        
        .status-value {
            font-weight: 500;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .logs {
            background: var(--card-dark);
            border-radius: 8px;
            padding: 20px;
            height: 250px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin-top: 30px;
            border-left: 4px solid var(--primary-color);
        }
        
        .logs h3 {
            margin-bottom: 15px;
            color: var(--primary-color);
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
            color: var(--error-color);
        }
        
        .log-success {
            color: var(--success-color);
        }
        
        .log-warning {
            color: #ffd740;
        }
        
        .error-message {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid var(--error-color);
            color: var(--error-color);
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            display: none;
            text-align: center;
            font-weight: 500;
        }
        
        .success-message {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            display: none;
            text-align: center;
            font-weight: 500;
        }
        
        .voice-feedback {
            position: fixed;
            bottom: 80px;
            right: 20px;
            padding: 12px 16px;
            border-radius: 5px;
            color: white;
            background: var(--success-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 9998;
            animation: fadeIn 0.3s;
            max-width: 300px;
            word-wrap: break-word;
            display: none;
        }
        
        .voice-feedback.error {
            background: var(--error-color);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #333;
        }
        
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab.active {
            border-bottom: 3px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Administrador de Comandos de Voz</h1>
            <p>Gestiona y prueba los comandos de voz del sistema</p>
        </div>
        
        <div class="navigation">
            <button onclick="window.location.href='{{ route('perfil') }}'"class="nav-btn active" id="voiceControlBtn">volver a inicio</button>
            <button onclick="window.location.href='{{ route('operacion1') }}'" class="nav-btn" id="gestureControlBtn">
                Control de Gestos
            </button> 
        </div>
        
        <div id="errorContainer" class="error-message"></div>
        <div id="successContainer" class="success-message"></div>
        
        <div class="tabs">
            
           <button type="button" id="tabComandos" class="tab active" data-tab="commands">Comandos</button>
           <button type="button" id="tabProbarComandos" class="tab" data-tab="test">Probar Comandos</button>
           <button type="button" id="tabConfiguracion" class="tab" data-tab="settings">Configuración</button>
        </div>
        
        <div class="tab-content active" id="commands-tab">
            <div class="admin-panels">
                <div class="panel">
                    <h2><i class="fas fa-list"></i> Lista de Comandos</h2>
                    <div class="command-list" id="commandsList">
                       
                    </div>
                </div>
                
                <div class="panel">
                    <h2><i class="fas fa-plus-circle"></i> Añadir/Editar Comando</h2>
                    <form id="commandForm">
                        <input type="hidden" id="commandId">
                        <div class="form-group">
                            <label for="commandPhrases">Frases de activación (separadas por |):</label>
                            <textarea id="commandPhrases" placeholder="Ej: ir a inicio|volver|inicio"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="commandAction">Acción asociada:</label>
                            <select id="commandAction">
                                <option value="">Seleccione una acción</option>
                                <option value="home">Ir a Inicio</option>
                                <option value="data">Ir a Datos</option>
                                <option value="module1">Módulo 1</option>
                                <option value="vos">Módulo 2 (Voz)</option>
                                <option value="estadisticas">Estadísticas</option>
                                <option value="gestos">Control por Gestos</option>
                                <option value="ayuda">Ayuda</option>
                                <option value="miperfil">Mi Perfil</option>
                                <option value="stop">Detener Micrófono</option>
                                <option value="start">Activar Micrófono</option>
                                <option value="login">Cerrar Sesión</option>
                                <option value="scroll_down">Desplazar abajo</option>
                                <option value="scroll_up">Desplazar arriba</option>
                                <option value="click-select">Seleccionar imagen</option>
                                <option value="click-subir">Subir imagen</option>
                                <option value="click-detectar">Detectar personas</option>
                                <option value="click-atras">Atrás</option>
                                <option value="click-borrar">Borrar todo</option>
                                <option value="click-next">Siguiente imagen</option>
                                <option value="click-prev">Imagen anterior</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="commandDescription">Descripción:</label>
                            <input type="text" id="commandDescription" placeholder="Descripción del comando">
                        </div>
                        <button type="button" id="saveCommandBtn" class="btn btn-success">Guardar Comando</button>
                        <button type="button" id="clearFormBtn" class="btn btn-secondary">Limpiar Formulario</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="tab-content" id="test-tab">
            <div class="admin-panels">
                <div class="panel">
                    <h2><i class="fas fa-microphone"></i> Probar Comandos</h2>
                    <div class="command-controls">
                        <button id="startTestBtn" class="btn">Iniciar Prueba</button>
                        <button id="stopTestBtn" class="btn btn-danger">Detener Prueba</button>
                        <div class="control-group">
                            <label>O prueba escribiendo un comando:</label>
                            <input type="text" id="testCommandInput" placeholder="Escribe un comando para probar">
                            <button id="testTextCommandBtn" class="btn btn-secondary">Probar Comando</button>
                        </div>
                    </div>
                </div>
                
                <div class="panel">
                    <h2><i class="fas fa-info-circle"></i> Resultados de Prueba</h2>
                    <div class="status-grid">
                        <div class="status-item">
                            <div class="status-label">Estado de Voz</div>
                            <div class="status-value" id="voiceStatus">Inactivo</div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Último Comando</div>
                            <div class="status-value" id="lastCommand">Ninguno</div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Coincidencia</div>
                            <div class="status-value" id="commandMatch">No</div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Acción Ejecutada</div>
                            <div class="status-value" id="actionExecuted">Ninguna</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tab-content" id="settings-tab">
            <div class="panel">
                <h2><i class="fas fa-cog"></i> Configuración Global</h2>
                <div class="form-group">
                    <label for="commandCooldown">Tiempo entre comandos (ms):</label>
                    <input type="number" id="commandCooldown" value="1000">
                </div>
                <div class="form-group">
                    <label for="languageSetting">Idioma de reconocimiento:</label>
                    <select id="languageSetting">
                        <option value="es-ES">Español (España)</option>
                        <option value="es-MX">Español (México)</option>
                        <option value="es-AR">Español (Argentina)</option>
                    </select>
                </div>
                <button id="saveSettingsBtn" class="btn btn-success">Guardar Configuración</button>
            </div>
        </div>
        
        <div class="logs" id="logContainer">
            <h3>Registro de Actividad</h3>
            <div class="log-entry log-system">[Sistema inicializado]</div>
        </div>
    </div>

    <div id="voiceFeedback" class="voice-feedback"></div>

    <script>
       
        let voiceRecognition = null;
        let commands = {};
        let isTesting = false;
        
        
        document.addEventListener('DOMContentLoaded', function() {
          
            if (window.voiceConfig && window.voiceConfig.commands) {
                commands = window.voiceConfig.commands;
                loadCommandsList();
            }
            
            // Configurar pestañas
            setupTabs();
            
            // Configurar botones
            setupButtons();
            
            // Inicializar el reconocimiento de voz
            initVoiceRecognition();
            
            log('Administrador de comandos inicializado', 'system');
        });
        
        // Configurar pestañas
        function setupTabs() {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Desactivar todas las pestañas
                    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Activar la pestaña seleccionada
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
        }
    
        function setupButtons() {
           
            document.getElementById('saveCommandBtn').addEventListener('click', saveCommand);
            
          
            document.getElementById('clearFormBtn').addEventListener('click', clearForm);
            
          
            document.getElementById('startTestBtn').addEventListener('click', startTest);
            document.getElementById('stopTestBtn').addEventListener('click', stopTest);
            document.getElementById('testTextCommandBtn').addEventListener('click', testTextCommand);
            
       
            document.getElementById('saveSettingsBtn').addEventListener('click', saveSettings);
        }
        
        
        function initVoiceRecognition() {
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                voiceRecognition = new SpeechRecognition();
                
                voiceRecognition.continuous = true;
                voiceRecognition.interimResults = false;
                voiceRecognition.lang = 'es-ES';
                
                voiceRecognition.onresult = function(event) {
                    const result = event.results[event.resultIndex];
                    if (result.isFinal) {
                        const transcript = result[0].transcript.trim();
                        if (transcript) {
                            processTestCommand(transcript);
                        }
                    }
                };
                
                voiceRecognition.onerror = function(event) {
                    showError(`Error en reconocimiento: ${event.error}`);
                    log(`Error en reconocimiento: ${event.error}`, 'system');
                };
                
                voiceRecognition.onend = function() {
                    if (isTesting) {
                        voiceRecognition.start();
                    }
                };
            } else {
                showError('El reconocimiento de voz no es compatible con este navegador');
                log('Reconocimiento de voz no compatible', 'system');
            }
        }
        
    
        function loadCommandsList() {
            const commandsList = document.getElementById('commandsList');
            commandsList.innerHTML = '';
            
            for (const phrases in commands) {
                const action = commands[phrases];
                
                const commandItem = document.createElement('div');
                commandItem.className = 'command-item';
                commandItem.innerHTML = `
                    <div>
                        <div class="command-phrase">"${phrases}"</div>
                        <div class="command-action">Acción: ${action}</div>
                    </div>
                    <div class="command-actions">
                        <button class="command-btn test" data-phrases="${phrases}">Probar</button>
                        <button class="command-btn edit" data-phrases="${phrases}" data-action="${action}">Editar</button>
                        <button class="command-btn delete" data-phrases="${phrases}">Eliminar</button>
                    </div>
                `;
                
                commandsList.appendChild(commandItem);
            }
            
        
            document.querySelectorAll('.command-btn.test').forEach(btn => {
                btn.addEventListener('click', function() {
                    const phrases = this.getAttribute('data-phrases');
                    testCommand(phrases.split('|')[0]); 
                });
            });
            
            document.querySelectorAll('.command-btn.edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const phrases = this.getAttribute('data-phrases');
                    const action = this.getAttribute('data-action');
                    editCommand(phrases, action);
                });
            });
            
            document.querySelectorAll('.command-btn.delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const phrases = this.getAttribute('data-phrases');
                    deleteCommand(phrases);
                });
            });
        }
        
        // Guardar comando
        function saveCommand() {
            const phrases = document.getElementById('commandPhrases').value.trim();
            const action = document.getElementById('commandAction').value;
            const description = document.getElementById('commandDescription').value.trim();
            
            if (!phrases || !action) {
                showError('Por favor complete todas las frases y seleccione una acción');
                return;
            }
            
            // Normalizar frases (eliminar espacios extra, etc.)
            const normalizedPhrases = phrases.split('|')
                .map(p => p.trim())
                .filter(p => p.length > 0)
                .join('|');
            
            // Agregar o actualizar el comando
            commands[normalizedPhrases] = action;
            
            // Actualizar la lista
            loadCommandsList();
            
            // Limpiar formulario
            clearForm();
            
            // Mostrar mensaje de éxito
            showSuccess('Comando guardado correctamente');
            log(`Comando guardado: "${normalizedPhrases}" -> "${action}"`, 'success');
            
            // Actualizar la configuración global
            if (window.voiceConfig) {
                window.voiceConfig.commands = commands;
            }
        }
        
        // Editar comando
        function editCommand(phrases, action) {
            document.getElementById('commandPhrases').value = phrases;
            document.getElementById('commandAction').value = action;
            document.getElementById('commandDescription').value = '';
            
            // Desplazarse al formulario
            document.getElementById('commandForm').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Eliminar comando
        function deleteCommand(phrases) {
            if (confirm(`¿Está seguro que desea eliminar el comando "${phrases}"?`)) {
                delete commands[phrases];
                loadCommandsList();
                showSuccess('Comando eliminado correctamente');
                log(`Comando eliminado: "${phrases}"`, 'system');
                
                // Actualizar la configuración global
                if (window.voiceConfig) {
                    window.voiceConfig.commands = commands;
                }
            }
        }
        
        // Limpiar formulario
        function clearForm() {
            document.getElementById('commandForm').reset();
            document.getElementById('commandId').value = '';
        }
        
        // Iniciar prueba de comandos
        function startTest() {
            if (!voiceRecognition) {
                showError('El reconocimiento de voz no está disponible');
                return;
            }
            
            try {
                voiceRecognition.start();
                isTesting = true;
                document.getElementById('voiceStatus').textContent = 'Escuchando';
                document.getElementById('startTestBtn').classList.add('active');
                showFeedback('Modo prueba activado - Escuchando comandos...');
                log('Prueba de comandos iniciada', 'system');
            } catch (error) {
                showError('Error al iniciar el reconocimiento de voz: ' + error.message);
                log(`Error al iniciar prueba: ${error.message}`, 'system');
            }
        }
        
        // Detener prueba de comandos
        function stopTest() {
            if (voiceRecognition && isTesting) {
                voiceRecognition.stop();
                isTesting = false;
                document.getElementById('voiceStatus').textContent = 'Inactivo';
                document.getElementById('startTestBtn').classList.remove('active');
                showFeedback('Prueba de comandos detenida');
                log('Prueba de comandos detenida', 'system');
            }
        }
        
        // Probar comando desde texto
        function testTextCommand() {
            const command = document.getElementById('testCommandInput').value.trim();
            if (command) {
                processTestCommand(command);
                document.getElementById('testCommandInput').value = '';
            } else {
                showError('Ingrese un comando para probar');
            }
        }
        
        // Procesar comando de prueba
        function processTestCommand(command) {
            if (!command) return;
            
            document.getElementById('lastCommand').textContent = command;
            log(`Comando recibido: "${command}"`, 'voice');
            
            let matched = false;
            let actionExecuted = 'Ninguna';
            
            // Buscar coincidencia en los comandos
            for (const phrases in commands) {
                const phrasesList = phrases.split('|');
                if (phrasesList.some(p => command.toLowerCase().includes(p.toLowerCase()))) {
                    matched = true;
                    actionExecuted = commands[phrases];
                    break;
                }
            }
            
            document.getElementById('commandMatch').textContent = matched ? 'Sí' : 'No';
            document.getElementById('actionExecuted').textContent = actionExecuted;
            
            if (matched) {
                showFeedback(`Comando reconocido: "${command}" -> "${actionExecuted}"`);
                log(`Acción ejecutada: ${actionExecuted}`, 'success');
            } else {
                showFeedback(`Comando no reconocido: "${command}"`, true);
                log(`Comando no reconocido: "${command}"`, 'warning');
            }
        }
        
        // Probar comando específico
        function testCommand(command) {
            document.getElementById('testCommandInput').value = command;
            testTextCommand();
        }
        
        // Guardar configuración
        function saveSettings() {
            const cooldown = parseInt(document.getElementById('commandCooldown').value) || 1000;
            const language = document.getElementById('languageSetting').value;
            
            if (voiceRecognition) {
                voiceRecognition.lang = language;
            }
            
            if (window.voiceRecognition) {
                window.voiceRecognition.commandCooldown = cooldown;
            }
            
            showSuccess('Configuración guardada correctamente');
            log(`Configuración actualizada - Cooldown: ${cooldown}ms, Idioma: ${language}`, 'system');
        }
        
        // Mostrar mensaje de error
        function showError(message) {
            const errorContainer = document.getElementById('errorContainer');
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
            
            setTimeout(() => {
                errorContainer.style.display = 'none';
            }, 5000);
        }
        
        // Mostrar mensaje de éxito
        function showSuccess(message) {
            const successContainer = document.getElementById('successContainer');
            successContainer.textContent = message;
            successContainer.style.display = 'block';
            
            setTimeout(() => {
                successContainer.style.display = 'none';
            }, 5000);
        }
        
        // Mostrar feedback
        function showFeedback(message, isError = false) {
            const feedback = document.getElementById('voiceFeedback');
            feedback.textContent = message;
            feedback.className = `voice-feedback ${isError ? 'error' : ''}`;
            feedback.style.display = 'block';
            
            setTimeout(() => {
                feedback.style.display = 'none';
            }, 3000);
        }
        
     
        function log(message, type = 'system') {
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.className = `log-entry log-${type}`;
            logEntry.textContent = `[${timestamp}] ${message}`;
            document.getElementById('logContainer').appendChild(logEntry);
            document.getElementById('logContainer').scrollTop = document.getElementById('logContainer').scrollHeight;
        }
    </script>

    @include('vistas-globales.vos-iu')
    @include('vistas-globales.vos-comandos')
    <script src="{{ asset('voiceRecognition.js') }}"></script>
</body>
</html>