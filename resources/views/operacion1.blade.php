<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control por Gestos Mejorado</title>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/control_utils/control_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: white;
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
            margin-bottom: 40px;
            padding: 20px 0;
            border-bottom: 2px solid #1e88e5;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.8;
            font-weight: 300;
        }

        .video-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .video-wrapper {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        #videoElement {
            width: 640px;
            height: 480px;
            background: #000;
            display: block;
        }

        #canvasElement {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn {
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

        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .panel {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 25px;
            border-left: 4px solid #1e88e5;
        }

        .panel h3 {
            margin-bottom: 20px;
            color: #1e88e5;
            font-size: 1.3rem;
            font-weight: 400;
        }

        .gesture-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .gesture-card {
            background: #252525;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .gesture-card.active {
            border-left: 3px solid #1e88e5;
            background: #2a2a2a;
        }

        .gesture-card.executing {
            border-left: 3px solid #69f0ae;
            background: rgba(105, 240, 174, 0.1);
            animation: executingPulse 1.5s infinite;
        }

        @keyframes executingPulse {
            0% { background: rgba(105, 240, 174, 0.1); }
            50% { background: rgba(105, 240, 174, 0.2); }
            100% { background: rgba(105, 240, 174, 0.1); }
        }

        .gesture-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }

        .gesture-name {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .gesture-desc {
            font-size: 12px;
            color: #bbbbbb;
        }

        .status-display {
            text-align: center;
            font-size: 1.2rem;
            margin: 20px 0;
            padding: 15px;
            background: #252525;
            border-radius: 8px;
        }

        .detection-info {
            background: #252525;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .detection-status {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .detection-bar {
            height: 10px;
            background: #333;
            border-radius: 5px;
            margin-top: 5px;
            overflow: hidden;
        }

        .detection-progress {
            height: 100%;
            background: #1e88e5;
            width: 0%;
            transition: width 0.3s;
        }

        .tips {
            background: #252525;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .tips h4 {
            color: #1e88e5;
            margin-bottom: 10px;
        }

        .tips ul {
            padding-left: 20px;
            font-size: 14px;
            color: #bbbbbb;
        }

        .tips li {
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .video-wrapper, #videoElement, #canvasElement {
                width: 100%;
                height: auto;
                aspect-ratio: 4/3;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Control por Gestos Mejorado</h1>
            <p>Sistema con reconocimiento preciso de manos</p>
        </div>

        <div class="video-container">
            <div class="video-wrapper">
                <video id="videoElement" autoplay muted playsinline></video>
                <canvas id="canvasElement"></canvas>
            </div>
        </div>

        <div class="controls">
            <button id="startBtn" class="btn">Iniciar Detección</button>
            <button id="stopBtn" class="btn">Detener</button>
        </div>

        <div class="detection-info">
            <div class="detection-status">
                <span>Estado de detección:</span>
                <span id="detectionStatus">Inactivo</span>
            </div>
            <div class="detection-status">
                <span>Confianza:</span>
                <span id="confidenceLevel">0%</span>
            </div>
            <div class="detection-bar">
                <div id="confidenceBar" class="detection-progress"></div>
            </div>
        </div>

        <div class="status-display">
            Gesto detectado: <span id="currentGesture">Ninguno</span>
        </div>

        <div class="dashboard">
            <div class="panel">
                <h3>Gestos Detectables</h3>
                <div class="gesture-grid">
                    <div id="card-thumbs_up" class="gesture-card">
                        <span class="gesture-icon">👍</span>
                        <div class="gesture-name">Pulgar Arriba</div>
                        <div class="gesture-desc">Solo pulgar extendido</div>
                    </div>
                    <div id="card-ok" class="gesture-card">
                        <span class="gesture-icon">👌</span>
                        <div class="gesture-name">OK</div>
                        <div class="gesture-desc">Pulgar e índice juntos</div>
                    </div>
                    <div id="card-peace" class="gesture-card">
                        <span class="gesture-icon">✌️</span>
                        <div class="gesture-name">Paz</div>
                        <div class="gesture-desc">Índice y medio extendidos</div>
                    </div>
                    <div id="card-pointing" class="gesture-card">
                        <span class="gesture-icon">👉</span>
                        <div class="gesture-name">Señalar</div>
                        <div class="gesture-desc">Solo índice extendido</div>
                    </div>
                    <div id="card-open_hand" class="gesture-card">
                        <span class="gesture-icon">🖐️</span>
                        <div class="gesture-name">Mano Abierta</div>
                        <div class="gesture-desc">Todos los dedos extendidos</div>
                    </div>
                    <div id="card-fist" class="gesture-card">
                        <span class="gesture-icon">✊</span>
                        <div class="gesture-name">Puño</div>
                        <div class="gesture-desc">Todos los dedos cerrados</div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <h3>Información de Detección</h3>
                <div id="landmarkInfo" class="landmark-info">
                    Esperando detección de manos...
                </div>
                
                <div class="tips">
                    <h4>Consejos para mejor detección:</h4>
                    <ul>
                        <li>Mantén la mano a 30-50 cm de la cámara</li>
                        <li>Buena iluminación sin reflejos</li>
                        <li>Muestra la palma hacia la cámara</li>
                        <li>Evita movimientos bruscos</li>
                        <li>Prueba diferentes ángulos si no te detecta</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Elementos del DOM
        const videoElement = document.getElementById('videoElement');
        const canvasElement = document.getElementById('canvasElement');
        const canvasCtx = canvasElement.getContext('2d');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const currentGestureElement = document.getElementById('currentGesture');
        const detectionStatus = document.getElementById('detectionStatus');
        const confidenceLevel = document.getElementById('confidenceLevel');
        const confidenceBar = document.getElementById('confidenceBar');
        const landmarkInfo = document.getElementById('landmarkInfo');
        
        // Variables de estado
        let hands;
        let camera;
        let isActive = false;
        let currentGesture = null;
        let detectionConfidence = 0;
        let lastGestureTime = 0;
        
        // Configuración
        const MIN_CONFIDENCE = 0.5; // Umbral mínimo de confianza para detección
        const GESTURE_HOLD_TIME = 800; // ms para considerar gesto mantenido
        
        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initializeHands();
            setupEventListeners();
        });

        function initializeHands() {
            hands = new Hands({
                locateFile: (file) => {
                    return `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`;
                }
            });
            
            // Configuración optimizada para mejor detección
            hands.setOptions({
                maxNumHands: 2,
                modelComplexity: 1,
                minDetectionConfidence: 0.5,  // Reducido para mayor sensibilidad
                minTrackingConfidence: 0.5,   // Reducido para mejor seguimiento
                selfieMode: true              // Mejor para cámaras frontales
            });
            
            hands.onResults(processResults);
        }

        function processResults(results) {
            // Actualizar UI con estado de detección
            updateDetectionUI(results);
            
            // Limpiar canvas
            canvasCtx.save();
            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
            
            // Dibujar imagen de video
            canvasCtx.drawImage(results.image, 0, 0, canvasElement.width, canvasElement.height);
            
            // Procesar detección de manos
            if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                const landmarks = results.multiHandLandmarks[0];
                const handedness = results.multiHandedness[0].label; // 'Left' o 'Right'
                
                // Dibujar landmarks y conexiones
                drawLandmarksAndConnections(landmarks, handedness);
                
                // Reconocer gesto con mayor precisión
                const gesture = recognizeGestureWithPrecision(landmarks, handedness);
                updateGestureState(gesture);
                
                // Mostrar información técnica
                displayTechnicalInfo(landmarks, handedness);
            } else {
                updateGestureState(null);
                landmarkInfo.innerHTML = 'No se detectan manos. Asegúrate de que tu mano esté visible.';
            }
            
            canvasCtx.restore();
        }

        function drawLandmarksAndConnections(landmarks, handedness) {
            // Color basado en la mano (izquierda/derecha)
            const handColor = handedness === 'Right' ? '#00d2d3' : '#ff6b6b';
            
            // Dibujar conexiones
            const connections = Hands.HAND_CONNECTIONS;
            canvasCtx.strokeStyle = handColor;
            canvasCtx.lineWidth = 3;
            
            for (const [start, end] of connections) {
                canvasCtx.beginPath();
                canvasCtx.moveTo(
                    landmarks[start].x * canvasElement.width,
                    landmarks[start].y * canvasElement.height
                );
                canvasCtx.lineTo(
                    landmarks[end].x * canvasElement.width,
                    landmarks[end].y * canvasElement.height
                );
                canvasCtx.stroke();
            }
            
            // Dibujar puntos de referencia
            canvasCtx.fillStyle = handColor;
            for (const landmark of landmarks) {
                canvasCtx.beginPath();
                canvasCtx.arc(
                    landmark.x * canvasElement.width,
                    landmark.y * canvasElement.height,
                    5, 0, 2 * Math.PI
                );
                canvasCtx.fill();
            }
            
            // Dibujar punto de referencia para la palma (mejor visualización)
            canvasCtx.fillStyle = '#ffffff';
            canvasCtx.beginPath();
            canvasCtx.arc(
                landmarks[0].x * canvasElement.width,
                landmarks[0].y * canvasElement.height,
                7, 0, 2 * Math.PI
            );
            canvasCtx.fill();
        }

        function recognizeGestureWithPrecision(landmarks, handedness) {
            // Calcular estados de cada dedo con mayor precisión
            const fingerStates = {
                thumb: isFingerExtendedPrecise(landmarks, 'thumb', handedness),
                index: isFingerExtendedPrecise(landmarks, 'index'),
                middle: isFingerExtendedPrecise(landmarks, 'middle'),
                ring: isFingerExtendedPrecise(landmarks, 'ring'),
                pinky: isFingerExtendedPrecise(landmarks, 'pinky')
            };
            
            // Contar dedos extendidos
            const extendedCount = Object.values(fingerStates).filter(Boolean).length;
            
            // Detección mejorada de gestos
            if (fingerStates.thumb && !fingerStates.index && !fingerStates.middle && !fingerStates.ring && !fingerStates.pinky) {
                return 'thumbs_up';
            }
            
            if (!fingerStates.thumb && fingerStates.index && !fingerStates.middle && !fingerStates.ring && !fingerStates.pinky) {
                return 'pointing';
            }
            
            if (!fingerStates.thumb && fingerStates.index && fingerStates.middle && !fingerStates.ring && !fingerStates.pinky) {
                return 'peace';
            }
            
            if (isOKGesturePrecise(landmarks, handedness)) {
                return 'ok';
            }
            
            if (extendedCount === 5) {
                return 'open_hand';
            }
            
            if (extendedCount === 0) {
                return 'fist';
            }
            
            return null;
        }

        function isFingerExtendedPrecise(landmarks, finger, handedness = 'Right') {
            const fingerIndices = {
                thumb: { tip: 4, pip: 2, dip: 3, mcp: 1 },
                index: { tip: 8, dip: 7, pip: 6, mcp: 5 },
                middle: { tip: 12, dip: 11, pip: 10, mcp: 9 },
                ring: { tip: 16, dip: 15, pip: 14, mcp: 13 },
                pinky: { tip: 20, dip: 19, pip: 18, mcp: 17 }
            };
            
            const { tip, pip, dip, mcp } = fingerIndices[finger];
            
            // Para el pulgar, cálculo especial considerando la mano (izquierda/derecha)
            if (finger === 'thumb') {
                const thumbDirection = handedness === 'Right' ? 1 : -1;
                const tipToPipAngle = Math.atan2(
                    landmarks[tip].y - landmarks[pip].y,
                    (landmarks[tip].x - landmarks[pip].x) * thumbDirection
                );
                
                // Ángulo debe estar en cierto rango para considerarse extendido
                return tipToPipAngle > -0.5 && tipToPipAngle < 0.5;
            }
            
            // Para otros dedos, verificamos múltiples ángulos para mayor precisión
            const tipToDipAngle = Math.atan2(
                landmarks[tip].y - landmarks[dip].y,
                landmarks[tip].x - landmarks[dip].x
            );
            
            const dipToPipAngle = Math.atan2(
                landmarks[dip].y - landmarks[pip].y,
                landmarks[dip].x - landmarks[pip].x
            );
            
            const pipToMcpAngle = Math.atan2(
                landmarks[pip].y - landmarks[mcp].y,
                landmarks[pip].x - landmarks[mcp].x
            );
            
            // Consideramos el dedo extendido si los ángulos están en cierto rango
            return tipToDipAngle < -0.3 && dipToPipAngle < -0.3 && pipToMcpAngle < -0.3;
        }

        function isOKGesturePrecise(landmarks, handedness) {
            const thumbTip = landmarks[4];
            const indexTip = landmarks[8];
            const middleTip = landmarks[12];
            
            // Calcular distancias entre dedos
            const thumbIndexDistance = Math.sqrt(
                Math.pow(thumbTip.x - indexTip.x, 2) + 
                Math.pow(thumbTip.y - indexTip.y, 2)
            );
            
            const thumbMiddleDistance = Math.sqrt(
                Math.pow(thumbTip.x - middleTip.x, 2) + 
                Math.pow(thumbTip.y - middleTip.y, 2)
            );
            
            // Verificar que pulgar e índice estén cerca y otros dedos lejos
            return thumbIndexDistance < 0.08 && 
                   thumbMiddleDistance > 0.15 &&
                   isFingerExtendedPrecise(landmarks, 'middle') &&
                   isFingerExtendedPrecise(landmarks, 'ring') &&
                   isFingerExtendedPrecise(landmarks, 'pinky');
        }

        function updateGestureState(gesture) {
            const now = Date.now();
            
            // Solo actualizar si ha pasado suficiente tiempo desde el último cambio
            if (now - lastGestureTime < 300) return;
            
            // Actualizar gesto actual
            if (gesture !== currentGesture) {
                currentGesture = gesture;
                currentGestureElement.textContent = gesture ? formatGestureName(gesture) : 'Ninguno';
                lastGestureTime = now;
                
                // Resaltar tarjeta correspondiente
                document.querySelectorAll('.gesture-card').forEach(card => {
                    card.classList.remove('active');
                });
                
                if (gesture) {
                    const card = document.getElementById(`card-${gesture}`);
                    if (card) {
                        card.classList.add('active');
                        setTimeout(() => card.classList.remove('active'), 500);
                    }
                }
            }
        }

        function formatGestureName(gesture) {
            return gesture.split('_').map(word => 
                word.charAt(0).toUpperCase() + word.slice(1)
            ).join(' ');
        }

        function displayTechnicalInfo(landmarks, handedness) {
            let info = `<strong>Mano detectada:</strong> ${handedness === 'Right' ? 'Derecha' : 'Izquierda'}<br>`;
            info += `<strong>Confianza:</strong> ${Math.round(detectionConfidence * 100)}%<br><br>`;
            
            // Mostrar coordenadas de puntos clave
            const keyPoints = [0, 4, 8, 12, 16, 20];
            info += '<strong>Puntos clave:</strong><br>';
            keyPoints.forEach(index => {
                const point = landmarks[index];
                info += `Punto ${index}: (${point.x.toFixed(2)}, ${point.y.toFixed(2)})<br>`;
            });
            
            landmarkInfo.innerHTML = info;
        }

        function updateDetectionUI(results) {
            // Calcular confianza de detección
            if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                detectionConfidence = Math.max(
                    results.multiHandWorldLandmarks[0].reduce((max, landmark) => 
                        Math.max(max, landmark.z), 0
                );
                
                // Normalizar y ajustar la confianza para mostrar
                const displayConfidence = Math.min(Math.max(detectionConfidence * 2, 0), 1);
                confidenceLevel.textContent = `${Math.round(displayConfidence * 100)}%`;
                confidenceBar.style.width = `${displayConfidence * 100}%`;
                
                detectionStatus.textContent = 'Detección activa';
            } else {
                detectionConfidence = 0;
                confidenceLevel.textContent = '0%';
                confidenceBar.style.width = '0%';
                detectionStatus.textContent = 'Buscando manos...';
            }
        }

        async function startDetection() {
            try {
                // Solicitar acceso a la cámara
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 640 },
                        height: { ideal: 480 },
                        facingMode: "user"
                    },
                    audio: false
                });
                
                videoElement.srcObject = stream;
                
                // Esperar a que el video esté listo
                await new Promise((resolve) => {
                    videoElement.onloadedmetadata = () => {
                        canvasElement.width = videoElement.videoWidth;
                        canvasElement.height = videoElement.videoHeight;
                        resolve();
                    };
                });
                
                // Iniciar detección
                camera = new Camera(videoElement, {
                    onFrame: async () => {
                        await hands.send({ image: videoElement });
                    },
                    width: videoElement.videoWidth,
                    height: videoElement.videoHeight
                });
                
                camera.start();
                isActive = true;
                
                // Actualizar UI
                startBtn.classList.add('active');
                startBtn.textContent = 'Detectando...';
                stopBtn.disabled = false;
                
            } catch (error) {
                alert('Error al acceder a la cámara: ' + error.message);
            }
        }

        function stopDetection() {
            if (camera) {
                camera.stop();
            }
            
            if (videoElement.srcObject) {
                videoElement.srcObject.getTracks().forEach(track => track.stop());
                videoElement.srcObject = null;
            }
            
            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
            isActive = false;
            
            // Actualizar UI
            startBtn.classList.remove('active');
            startBtn.textContent = 'Iniciar Detección';
            stopBtn.disabled = true;
            
            currentGestureElement.textContent = 'Ninguno';
            detectionStatus.textContent = 'Inactivo';
            confidenceLevel.textContent = '0%';
            confidenceBar.style.width = '0%';
        }

        function setupEventListeners() {
            startBtn.addEventListener('click', startDetection);
            stopBtn.addEventListener('click', stopDetection);
            
            // Inicializar botón de detener como desactivado
            stopBtn.disabled = true;
        }
    </script>
</body>
</html>