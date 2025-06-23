class GestureDetector {
    constructor() {
        this.isActive = false;
        this.hands = null;
        this.camera = null;
    }

    async init() {
        return new Promise((resolve, reject) => {
            try {
                this.hands = new Hands({
                    locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
                });

                this.hands.setOptions({
                    maxNumHands: 1,
                    modelComplexity: 1,
                    minDetectionConfidence: 0.7,
                    minTrackingConfidence: 0.5
                });

                this.hands.onResults = (results) => {
                    if (this.isActive && results.multiHandLandmarks && window.gestureCommands) {
                        const gesture = this.detectGesture(results.multiHandLandmarks[0]);
                        if (gesture) window.gestureCommands.execute(gesture);
                    }
                };

                resolve();
            } catch (error) {
                reject(error);
            }
        });
    }

    async start(videoElement) {
        try {
            if (!this.hands) await this.init();
            
            this.camera = new Camera(videoElement, {
                onFrame: async () => {
                    if (this.isActive) await this.hands.send({ image: videoElement });
                },
                width: 640,
                height: 480
            });

            await this.camera.start();
            this.isActive = true;
            return true;
        } catch (error) {
            console.error("Error al iniciar cámara:", error);
            return false;
        }
    }

    async stop() {
        try {
            if (this.camera) await this.camera.stop();
            this.isActive = false;
            return true;
        } catch (error) {
            console.error("Error al detener cámara:", error);
            return false;
        }
    }

    detectGesture(landmarks) {
        // ... (tu lógica de detección de gestos)
    }
}

// Inicialización segura
if (!window.gestureDetector) {
    window.gestureDetector = new GestureDetector();
}