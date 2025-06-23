class GestureCommands {
    constructor() {
        this.commands = {
            // Navegación
            'thumbs_up': () => this.navigate('home'),
            'thumbs_down': () => this.navigate('back'),
            'point_left': () => window.history.back(),
            'point_right': () => this.navigate('next'),
            
            // Acciones básicas
            'fist': () => this.scroll('down'),
            'open_hand': () => this.toggleDetection(),
            'victory': () => this.showHelp(),
            
            // Acciones avanzadas
            'call_me': () => this.toggleVoiceControl(),
            'rock': () => this.specialAction('rock'),
            'vulcan': () => this.logout()
        };
    }

    execute(gesture) {
        if (this.commands[gesture]) {
            this.showFeedback(`Gesto: ${gesture}`);
            this.commands[gesture]();
        }
    }

    // ... (métodos auxiliares iguales)

    toggleVoiceControl() {
        if (window.voiceRecognition) {
            window.voiceRecognition.isListening ? 
                window.voiceRecognition.stop() : 
                window.voiceRecognition.start();
        }
    }

    specialAction(type) {
        console.log(`Acción especial: ${type}`);
        // Implementa acciones personalizadas aquí
    }

    logout() {
        if (window.voiceConfig?.actions?.logout) {
            window.location.href = window.voiceConfig.actions.logout;
        }
    }
}

window.gestureCommands = new GestureCommands();