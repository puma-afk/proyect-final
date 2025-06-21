<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control por Gestos</title>
    <style>
        :root {
            --primary-color: #1a73e8;
            --neon-blue: #00f2ff;
            --background-dark: #0a0a0a;
            --card-dark: #1a1a1a;
            --text-light: #f0f0f0;
            --text-muted: #aaaaaa;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: var(--background-dark);
            color: var(--text-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1000px;
            width: 100%;
            background: var(--card-dark);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
        }
        
        .header {
            background: linear-gradient(135deg, #0d2b64, #1a73e8);
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid var(--primary-color);
        }
        
        h1 {
            color: var(--text-light);
            margin: 0;
            font-size: 2rem;
            letter-spacing: 1px;
        }
        
        .video-wrapper {
            position: relative;
            padding: 20px;
            display: flex;
            justify-content: center;
            background: rgba(10,10,10,0.7);
        }
        
        .video-container {
            position: relative;
            width: 100%;
            max-width: 700px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.3);
            border: 2px solid var(--neon-blue);
        }
        
        #video {
            width: 100%;
            display: block;
            background: #000;
        }
        
        #canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 25px;
            background: rgba(26, 115, 232, 0.1);
            border-top: 1px solid rgba(26, 115, 232, 0.3);
            border-bottom: 1px solid rgba(26, 115, 232, 0.3);
        }
        
        button {
            padding: 14px 28px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            letter-spacing: 0.5px;
        }
        
        button:hover {
            background: #0d5fd6;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }
        
        button:disabled {
            background: #333;
            color: var(--text-muted);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .gesture-status {
            padding: 25px;
            text-align: center;
            background: rgba(10,10,10,0.7);
        }
        
        #gestureOutput {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        
        #gestureText {
            font-weight: bold;
            color: var(--neon-blue);
            text-shadow: 0 0 8px rgba(0, 242, 255, 0.5);
        }
        
        .gesture-feedback {
            font-size: 4rem;
            margin: 20px 0;
            min-height: 80px;
            text-shadow: 0 0 15px rgba(0, 242, 255, 0.7);
        }
        
        .progress-container {
            padding: 0 30px 30px;
        }
        
        .progress-bar {
            height: 10px;
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--neon-blue));
            width: 0%;
            transition: width 0.1s linear;
            border-radius: 5px;
        }
        
        .gesture-commands {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 30px;
        }
        
        .command-card {
            padding: 25px;
            background: rgba(30, 30, 30, 0.8);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .command-card h3 {
            margin-top: 0;
            color: var(--text-light);
            font-size: 1.3rem;
        }
        
        .command-card p {
            color: var(--text-muted);
            margin-bottom: 0;
            font-size: 0.95rem;
        }
        
        .command-card.active {
            background: rgba(26, 115, 232, 0.2);
            border-left: 4px solid var(--neon-blue);
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        
        .command-card.executing {
            background: rgba(0, 242, 255, 0.15);
            border-left: 4px solid var(--neon-blue);
            animation: pulse 0.6s ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: translateY(-8px) scale(1); }
            50% { transform: translateY(-8px) scale(1.05); }
            100% { transform: translateY(-8px) scale(1); }
        }
        
        .action-log {
            padding: 25px;
            background: rgba(15, 15, 15, 0.9);
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        
        .action-log h3 {
            margin-top: 0;
            color: var(--neon-blue);
            border-bottom: 1px solid rgba(0, 242, 255, 0.3);
            padding-bottom: 12px;
            font-size: 1.2rem;
        }
        
        #actionLog {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .log-entry {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 0.9rem;
        }
        
        .log-entry.error {
            color: #ff6b6b;
        }
        
        .log-entry.success {
            color: #66ff99;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--neon-blue);
        }
        
        /* Efecto neón para el contenedor de video */
        @keyframes neon-glow {
            0% { box-shadow: 0 0 10px rgba(0, 242, 255, 0.3); }
            50% { box-shadow: 0 0 25px rgba(0, 242, 255, 0.6); }
            100% { box-shadow: 0 0 10px rgba(0, 242, 255, 0.3); }
        }
        
        .video-container {
            animation: neon-glow 3s infinite alternate;
        }
        .nav-btn {
    background: transparent;
    border: 1px solid var(--neon-blue);
    color: var(--neon-blue);
    padding: 14px 28px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 0 10px rgba(0, 242, 255, 0.3);
}

.nav-btn:hover {
    background: rgba(0, 242, 255, 0.1);
    box-shadow: 0 0 15px rgba(0, 242, 255, 0.5);
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Control por Gestos Avanzado</h1>
        </div>
        
        <div class="video-wrapper">
            <div class="video-container">
                <video id="video" width="640" height="480" autoplay muted playsinline></video>
                <canvas id="canvas" width="640" height="480"></canvas>
            </div>
        </div>
        
        <div class="gesture-status">
            <div class="gesture-feedback" id="gestureFeedback"></div>
            <div id="gestureOutput">
                Gesto detectado: <span id="gestureText">Esperando...</span>
            </div>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress" id="gestureProgress"></div>
            </div>
        </div>
        
        <div class="controls">
            <button id="startBtn">Iniciar Detección</button>
            <button id="stopBtn">Detener</button>
            <button onclick="window.location.href='{{ route('modulo2') }}'" id="backToVoiceBtn" class="nav-btn">Volver a Control de Voz</button>
        </div>
        
        <div class="gesture-commands">
            <div class="command-card" id="cmd-open-hand">
                <h3>🖐️ Mano abierta</h3>
                <p>Acción: <strong>Detener detección</strong></p>
            </div>
            <div class="command-card" id="cmd-fist">
                <h3>👊 Puño cerrado</h3>
                <p>Acción: <strong>Scroll hacia abajo</strong></p>
            </div>
            <div class="command-card" id="cmd-thumbs-up">
                <h3>👍 Pulgar arriba</h3>
                <p>Acción: <strong>Ir a Perfil</strong></p>
            </div>
            <div class="command-card" id="cmd-palm">
                <h3>✋ Palma extendida</h3>
                <p>Acción: <strong>Mostrar ayuda</strong></p>
            </div>
            <div class="command-card" id="cmd-back">
                <h3>👈 Volver</h3>
                <p>Acción: <strong>Ir a Control de Voz</strong></p>
            </div>
        </div>
        
        <div class="action-log">
            <h3>Registro de Acciones</h3>
            <div id="actionLog"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/handpose"></script>
    
    <script>
        // Elementos del DOM
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const gestureText = document.getElementById('gestureText');
        const actionLog = document.getElementById('actionLog');
        const gestureFeedback = document.getElementById('gestureFeedback');
        const gestureProgress = document.getElementById('gestureProgress');
        
        // Variables de estado
        let model;
        let isRunning = false;
        let animationId;
        let lastGesture = null;
        let gestureStartTime = 0;
        let lastCommandTime = 0;
        const GESTURE_HOLD_TIME = 700; // 700ms para activar comando
        const COMMAND_COOLDOWN = 800; // 800ms entre comandos
        const GESTURE_PRIORITY = ['open_hand', 'thumbs_up', 'fist', 'palm']; // Prioridad de gestos
        
        // Mapeo de gestos a comandos (actualizado)
        const GESTURE_COMMANDS = {
            'open_hand': {
                name: '🖐️ Mano abierta',
                action: stopDetection,
                cardId: 'cmd-open-hand',
                emoji: '🖐️',
                priority: 3
            },
            'fist': {
                name: '👊 Puño cerrado',
                action: scrollDown,
                cardId: 'cmd-fist',
                emoji: '👊',
                priority: 2
            },
            'thumbs_up': {
                name: '👍 Pulgar arriba',
                action: goToProfile,
                cardId: 'cmd-thumbs-up',
                emoji: '👍',
                priority: 4
            },
            'palm': {
                name: '✋ Palma extendida',
                action: showHelp,
                cardId: 'cmd-palm',
                emoji: '✋',
                priority: 1
            }
             'back': {
               name: '👈 Volver',
               action: goToVoiceControl,
               cardId: 'cmd-back',
               emoji: '👈',
               priority: 5
            }
        };
        
        // Inicializar la aplicación
        async function init() {
            try {
                model = await handpose.load();
                console.log('Modelo Handpose cargado');
                setupEventListeners();
                stopBtn.disabled = true;
                logAction("Sistema de gestos listo. Inicia la detección.", false, true);
            } catch (error) {
                console.error('Error al cargar el modelo:', error);
                logAction('Error al cargar el modelo de detección', true);
            }
        }
        
        // Configurar event listeners
        function setupEventListeners() {
            startBtn.addEventListener('click', startDetection);
            stopBtn.addEventListener('click', stopDetection);
            
           
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && isRunning) {
                    stopDetection();
                }
            });
            document.getElementById('backToVoiceBtn').addEventListener('click', goToVoiceControl);
        }
        
        // Iniciar la detección
        async function startDetection() {
            if (isRunning) return;
            
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 640, height: 480, facingMode: "user" },
                    audio: false
                });
                
                video.srcObject = stream;
                isRunning = true;
                startBtn.disabled = true;
                stopBtn.disabled = false;
                logAction("Detección de gestos iniciada", false, true);
                
                await new Promise((resolve) => {
                    video.onloadedmetadata = () => {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        resolve();
                    };
                });
                
                detectGestures();
            } catch (error) {
                console.error('Error al acceder a la cámara:', error);
                logAction("Error al acceder a la cámara. Asegúrate de permitir el acceso.", true);
            }
        }
        
        // Detener la detección
        function stopDetection() {
            if (!isRunning) return;
            
            cancelAnimationFrame(animationId);
            isRunning = false;
            startBtn.disabled = false;
            stopBtn.disabled = true;
            
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            gestureFeedback.textContent = '';
            gestureProgress.style.width = '0%';
            logAction("Detección detenida", false, true);
            resetGestureState();
        }
        
        // Bucle principal de detección
        async function detectGestures() {
            if (!isRunning) return;
            
            try {
                const predictions = await model.estimateHands(video);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                if (predictions.length > 0) {
                    const landmarks = predictions[0].landmarks;
                    drawLandmarks(landmarks);
                    
                    const gesture = recognizeGesture(landmarks);
                    updateGestureUI(gesture);
                    
                    if (gesture) {
                        checkGestureDuration(gesture);
                    } else {
                        resetGestureState();
                    }
                } else {
                    resetGestureState();
                }
                
                animationId = requestAnimationFrame(detectGestures);
            } catch (error) {
                console.error('Error en detección:', error);
                logAction("Error en detección de gestos", true);
                stopDetection();
            }
        }
        
        // Actualizar la UI según el gesto detectado
        function updateGestureUI(gesture) {
            if (!gesture) {
                gestureText.textContent = "No se reconoce el gesto";
                gestureFeedback.textContent = '';
                return;
            }
            
            const command = GESTURE_COMMANDS[gesture];
            if (command) {
                gestureText.textContent = `Detectado: ${command.name}`;
                gestureFeedback.textContent = command.emoji;
                highlightCommandCard(command.cardId);
            }
        }
        
        // Resaltar la tarjeta de comando activa
        function highlightCommandCard(cardId) {
            document.querySelectorAll('.command-card').forEach(card => {
                card.classList.remove('active');
            });
            
            if (cardId) {
                const card = document.getElementById(cardId);
                if (card) card.classList.add('active');
            }
        }
        // boton volver
        function goToVoiceControl() {
             logAction("Redirigiendo a Control de Voz", false, true);
              window.location.href = "{{ route('modulo2') }}"; 
        }
        
        // Verificar si el gesto se mantiene el tiempo suficiente
        function checkGestureDuration(gesture) {
            const now = Date.now();
            
            if (gesture !== lastGesture) {
                lastGesture = gesture;
                gestureStartTime = now;
                gestureProgress.style.width = '0%';
                return;
            }
            
            const duration = now - gestureStartTime;
            const progress = Math.min(100, (duration / GESTURE_HOLD_TIME) * 100);
            gestureProgress.style.width = `${progress}%`;
            
            if (duration >= GESTURE_HOLD_TIME && (now - lastCommandTime) > COMMAND_COOLDOWN) {
                // Verificar prioridad de gestos para evitar solapamiento
                if (shouldExecuteGesture(gesture)) {
                    executeCommand(gesture);
                    gestureStartTime = now;
                    lastCommandTime = now;
                }
            }
        }
        
        // Determinar si el gesto debe ejecutarse (control de solapamiento)
        function shouldExecuteGesture(currentGesture) {
            if (!lastGesture) return true;
            
            const currentPriority = GESTURE_COMMANDS[currentGesture]?.priority || 0;
            const lastPriority = GESTURE_COMMANDS[lastGesture]?.priority || 0;
            
            // Solo permitir ejecución si el gesto actual tiene mayor o igual prioridad
            return currentPriority >= lastPriority;
        }
        
        // Resetear estado del gesto
        function resetGestureState() {
            lastGesture = null;
            gestureStartTime = 0;
            gestureText.textContent = "Acerca tu mano a la cámara";
            gestureFeedback.textContent = '';
            gestureProgress.style.width = '0%';
            highlightCommandCard(null);
        }
        
        // Ejecutar comando asociado al gesto
        function executeCommand(gesture) {
            const command = GESTURE_COMMANDS[gesture];
            if (command) {
                logAction(`Ejecutando: ${command.name}`, false, true);
                
                // Feedback visual de comando ejecutado
                const card = document.getElementById(command.cardId);
                if (card) {
                    card.classList.add('executing');
                    setTimeout(() => card.classList.remove('executing'), 600);
                }
                
                command.action();
            }
        }
        
        // Funciones de comandos actualizadas
        function showHelp() {
            logAction("Mostrando ayuda", false, true);
            alert("AYUDA\n\n✋ Palma extendida: Mostrar ayuda\n👊 Puño cerrado: Scroll abajo\n👍 Pulgar arriba: Ir a Perfil\n🖐️ Mano abierta: Detener detección");
        }
        
        function goToProfile() {
            logAction("Redirigiendo a la página 'Perfil'", false, true);
            // Redirección real a perfil.html
           window.location.href = "{{ route('perfil') }}"; 
        }
        
        function scrollDown() {
            window.scrollBy({
                top: window.innerHeight * 0.8,
                behavior: 'smooth'
            });
            logAction("Desplazamiento hacia abajo", false, true);
        }
        
        // Registrar acción en el log
        function logAction(message, isError = false, isSuccess = false) {
            const logEntry = document.createElement('div');
            logEntry.className = `log-entry ${isError ? 'error' : ''} ${isSuccess ? 'success' : ''}`;
            logEntry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            actionLog.prepend(logEntry);
            
            // Limitar el número de entradas en el log
            if (actionLog.children.length > 15) {
                actionLog.removeChild(actionLog.lastChild);
            }
        }
        
        // Función mejorada para reconocer gestos
        function recognizeGesture(landmarks) {
            // Obtener puntos clave
            const thumbTip = landmarks[4];
            const indexTip = landmarks[8];
            const middleTip = landmarks[12];
            const ringTip = landmarks[16];
            const pinkyTip = landmarks[20];
            const wrist = landmarks[0];
            
            // Calcular distancias entre dedos y muñeca
            const thumbDist = distance(thumbTip, wrist);
            const indexDist = distance(indexTip, wrist);
            const middleDist = distance(middleTip, wrist);
            const ringDist = distance(ringTip, wrist);
            const pinkyDist = distance(pinkyTip, wrist);
            
            // Determinar qué dedos están extendidos
            const fingersExtended = {
                thumb: isFingerExtended(landmarks, [1, 2, 3, 4], wrist),
                index: isFingerExtended(landmarks, [5, 6, 7, 8], wrist),
                middle: isFingerExtended(landmarks, [9, 10, 11, 12], wrist),
                ring: isFingerExtended(landmarks, [13, 14, 15, 16], wrist),
                pinky: isFingerExtended(landmarks, [17, 18, 19, 20], wrist)
            };
            
            const extendedCount = Object.values(fingersExtended).filter(b => b).length;
            
            // Detección de gestos mejorada
            // Pulgar arriba (solo pulgar extendido hacia arriba)
            if (fingersExtended.thumb && !fingersExtended.index && !fingersExtended.middle && 
                !fingersExtended.ring && !fingersExtended.pinky && thumbTip[1] < wrist[1] - 40) {
                return 'thumbs_up';
            }
            
            // Palma extendida (todos los dedos extendidos y juntos)
            if (extendedCount >= 4 && fingersTogether(landmarks)) {
                return 'palm';
            }
            
            // Mano abierta (todos los dedos extendidos pero no necesariamente juntos)
            if (extendedCount >= 4) {
                return 'open_hand';
            }
            
            // Puño (ningún dedo extendido o solo pulgar)
            if (extendedCount <= 1 && !fingersExtended.index && !fingersExtended.middle && 
                !fingersExtended.ring && !fingersExtended.pinky) {
                return 'fist';
            }
            // boton volver
             if (fingersExtended.index && !fingersExtended.middle && !fingersExtended.ring && 
                 !fingersExtended.pinky && indexTip[0] < wrist[0] - 50) {
                 return 'back';
            }
            
            return null;
        }
        
        // Función para verificar si los dedos están juntos
        function fingersTogether(landmarks) {
            const tips = [
                landmarks[8],  // Índice
                landmarks[12], // Medio
                landmarks[16], // Anular
                landmarks[20]  // Meñique
            ];
            
            // Calcular distancias entre puntas de dedos
            const threshold = 30; // Distancia máxima para considerar dedos juntos
            for (let i = 0; i < tips.length - 1; i++) {
                for (let j = i + 1; j < tips.length; j++) {
                    if (distance(tips[i], tips[j]) > threshold) {
                        return false;
                    }
                }
            }
            return true;
        }
        
        // Función para calcular distancia entre puntos
        function distance(point1, point2) {
            return Math.sqrt(
                Math.pow(point1[0] - point2[0], 2) + 
                Math.pow(point1[1] - point2[1], 2)
            );
        }
        
        // Función para dibujar landmarks con colores diferentes por dedo
        function drawLandmarks(landmarks) {
            // Colores para cada dedo
            const colors = ['#FF5252', '#4CAF50', '#2196F3', '#FFEB3B', '#E91E63'];
            
            // Dibujar puntos y conexiones para cada dedo
            const fingerJoints = [
                [0, 1, 2, 3, 4],    // Pulgar
                [0, 5, 6, 7, 8],     // Índice
                [0, 9, 10, 11, 12],  // Medio
                [0, 13, 14, 15, 16], // Anular
                [0, 17, 18, 19, 20]  // Meñique
            ];
            
            fingerJoints.forEach((finger, fingerIdx) => {
                // Dibujar conexiones
                ctx.strokeStyle = colors[fingerIdx];
                ctx.lineWidth = 3;
                
                for (let i = 0; i < finger.length - 1; i++) {
                    const start = landmarks[finger[i]];
                    const end = landmarks[finger[i + 1]];
                    
                    ctx.beginPath();
                    ctx.moveTo(start[0], start[1]);
                    ctx.lineTo(end[0], end[1]);
                    ctx.stroke();
                }
                
                // Dibujar puntos
                ctx.fillStyle = colors[fingerIdx];
                for (let i = 1; i < finger.length; i++) {
                    const point = landmarks[finger[i]];
                    ctx.beginPath();
                    ctx.arc(point[0], point[1], 5, 0, 2 * Math.PI);
                    ctx.fill();
                }
            });
            
            // Dibujar punto de la muñeca
            ctx.fillStyle = '#FFFFFF';
            const wrist = landmarks[0];
            ctx.beginPath();
            ctx.arc(wrist[0], wrist[1], 7, 0, 2 * Math.PI);
            ctx.fill();
        }
        
        // Función para determinar si un dedo está extendido
        function isFingerExtended(landmarks, indices, wrist) {
            const tip = landmarks[indices[indices.length - 1]];
            const dip = landmarks[indices[indices.length - 2]];
            const pip = landmarks[indices[indices.length - 3]];
            
            // Calculamos ángulos entre las articulaciones
            const angle1 = calculateAngle(pip, dip, tip);
            const angle2 = calculateAngle(landmarks[0], pip, dip);
            
            // Un dedo está extendido si los ángulos son mayores a 150 grados
            return angle1 > 150 && angle2 > 150 && tip[1] < wrist[1];
        }
        
        // Función para calcular ángulo entre 3 puntos
        function calculateAngle(a, b, c) {
            const ab = { x: b[0] - a[0], y: b[1] - a[1] };
            const cb = { x: b[0] - c[0], y: b[1] - c[1] };
            
            const dot = (ab.x * cb.x + ab.y * cb.y);
            const cross = (ab.x * cb.y - ab.y * cb.x);
            
            const alpha = Math.atan2(cross, dot);
            return (Math.abs(alpha * 180 / Math.PI));
        }
        
        // Iniciar la aplicación
        window.addEventListener('load', init);
    </script>
     @include('vistas-globales.vos-iu')
    @include('vistas-globales.vos-comandos') 
    <script src="{{ asset('voiceRecognition.js') }}"></script>
</body>
</html>