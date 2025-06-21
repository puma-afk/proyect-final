class VoiceRecognition {
    constructor() {
        this.recognition = null;
        this.isListening = false;
        this.lastCommandTime = 0;
        this.commandCooldown = 1000; // 1 segundo entre comandos
        this.init();
    }

    init() {
        try {
            if (window.SpeechRecognition || window.webkitSpeechRecognition) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                this.recognition = new SpeechRecognition();
                
                this.recognition.continuous = true;
                this.recognition.interimResults = false;
                this.recognition.lang = 'es-ES';
                
                this.bindEvents();
                
                // Iniciar automáticamente si estaba activo
                if (this.getPersistedState()) {
                    this.start();
                }
            } else {
                throw new Error("API de reconocimiento de voz no soportada");
            }
        } catch (error) {
            console.error("Error al inicializar reconocimiento de voz:", error);
            this.showErrorNotification("Error al iniciar el reconocimiento de voz");
        }
    }

    bindEvents() {
        if (!this.recognition) return;

        this.recognition.onresult = (event) => {
            const now = Date.now();
            if (now - this.lastCommandTime < this.commandCooldown) return;
            
            const lastResultIndex = event.results.length - 1;
            const commandText = event.results[lastResultIndex][0].transcript.trim(); // Asegura trim()

            this.processCommand(commandText);
            this.lastCommandTime = now; // Reiniciar el contador de cooldown después de procesar
        };

        this.recognition.onerror = (event) => {
            console.error("Error en el reconocimiento de voz:", event.error);
            let errorMessage = "Ocurrió un error en el reconocimiento de voz.";
            switch (event.error) {
                case 'no-speech':
                    errorMessage = "No se detectó voz. Intenta hablar más claro o más alto.";
                    break;
                case 'not-allowed':
                    errorMessage = "Permiso de micrófono denegado. Por favor, habilítalo en la configuración de tu navegador.";
                    break;
                case 'aborted':
                    errorMessage = "El reconocimiento de voz fue abortado.";
                    break;
                case 'network':
                    errorMessage = "Error de red al intentar acceder al servicio de voz.";
                    break;
                case 'bad-grammar':
                    errorMessage = "Gramática no válida.";
                    break;
                case 'language-not-supported':
                    errorMessage = "Idioma no soportado.";
                    break;
                case 'service-not-allowed':
                    errorMessage = "El servicio de reconocimiento de voz no está permitido.";
                    break;
                case 'audio-capture':
                    errorMessage = "Problema con la captura de audio. Verifica tu micrófono.";
                    break;
                default:
                    break;
            }
            this.showErrorNotification(errorMessage);
            this.updateVoiceButtonState(false); // Reflejar que no está escuchando
            this.isListening = false; // Actualizar estado
        };

        this.recognition.onend = () => {
            console.log("Reconocimiento de voz terminado.");
            if (this.isListening) { // Solo si queremos que sea continuo y no se detuvo manualmente
                // console.log("Reiniciando reconocimiento...");
                // this.recognition.start(); // Esto puede causar bucles si hay muchos errores
            }
            this.updateVoiceButtonState(this.isListening); // Asegura que el botón refleje el estado
        };

        // Evento para el botón global de voz
        const globalVoiceBtn = document.getElementById('globalVoiceBtn');
        if (globalVoiceBtn) {
            globalVoiceBtn.addEventListener('click', () => {
                if (this.isListening) {
                    this.stop();
                } else {
                    this.start();
                }
            });
        }
    }

    start() {
        if (this.recognition && !this.isListening) {
            try {
                this.recognition.start();
                this.isListening = true;
                this.showFeedbackNotification("Escuchando comandos...");
                this.updateVoiceButtonState(true);
                this.persistState(true);
                console.log("Reconocimiento de voz iniciado.");
            } catch (e) {
                console.error("Error al iniciar reconocimiento:", e);
                this.showErrorNotification("No se pudo iniciar el reconocimiento de voz. Verifica permisos o reinicia.");
                this.isListening = false;
                this.updateVoiceButtonState(false);
            }
        }
    }

    stop() {
        if (this.recognition && this.isListening) {
            this.recognition.stop();
            this.isListening = false;
            this.showFeedbackNotification("Micrófono desactivado.");
            this.updateVoiceButtonState(false);
            this.persistState(false);
            console.log("Reconocimiento de voz detenido.");
        }
    }

    // persistir el estado de escucha en localStorage
    persistState(state) {
        localStorage.setItem('voiceRecognitionActive', state);
    }

    // obtener el estado de escucha desde localStorage
    getPersistedState() {
        return localStorage.getItem('voiceRecognitionActive') === 'true';
    }

    processCommand(commandText) {
        const now = Date.now();
        if (now - this.lastCommandTime < this.commandCooldown) {
            // console.log("En cooldown, ignorando comando:", commandText);
            return; // Ignorar comandos si estamos en cooldown
        }

        
        // Elimina acentos, convierte a minúsculas, quita signos de puntuación y espacios extra
        const normalizedCommand = commandText
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "") // Elimina acentos
            .replace(/[¿?¡!.,;:]/g, "")      
            .trim(); // Elimina espacios al inicio/final
        this.showFeedbackNotification(`Comando reconocido: "${normalizedCommand}"`);
        console.log("Comando normalizado para procesar:", normalizedCommand);

        if (!window.voiceConfig || !window.voiceConfig.commands) {
            console.error("Error: window.voiceConfig.commands no está definido.");
            this.showErrorNotification("Error de configuración de comandos de voz.");
            return;
        }

        let commandFound = false;
        for (const patternString in window.voiceConfig.commands) {
            // Normaliza el patrón de la misma manera que el comando reconocido
            const normalizedPatternString = patternString.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            // Divide el patrón en frases individuales usando '|'
            const phrases = normalizedPatternString.split('|');

            // Busca una coincidencia exacta dentro de las frases normalizadas
            if (phrases.includes(normalizedCommand)) {
                const action = window.voiceConfig.commands[patternString];
                console.log(`Comando "${normalizedCommand}" coincide con acción: "${action}"`);
                this.executeAction(action);
                this.lastCommandTime = now; // Reiniciar cooldown
                commandFound = true;
                break; // Salir del bucle una vez que se encuentra un comando
            }
        }

        if (!commandFound) {
            this.showErrorNotification(`Comando no reconocido: "${normalizedCommand}"`);
            console.warn("No se encontró ninguna coincidencia para el comando:", normalizedCommand);
        }
    }

    executeAction(action) {
        if (!window.voiceConfig || !window.voiceConfig.routes || !window.voiceConfig.actions) {
            console.error("Error: Configuración de rutas o acciones de voz no definida.");
            this.showErrorNotification("Error interno de configuración de voz.");
            return;
        }

        switch (action) {
            case 'home':
                this.navigateTo(window.voiceConfig.routes.home);
                break;
            case 'data':
                this.navigateTo(window.voiceConfig.routes.data);
                break;
            case 'module1':
                this.navigateTo(window.voiceConfig.routes.module1);
                break;
            case 'vos':
                this.navigateTo(window.voiceConfig.routes.vos);
                break;
             case 'estadisticas':
                this.navigateTo(window.voiceConfig.routes.estadisticas);
                break;
             case 'gestos':
                this.navigateTo(window.voiceConfig.routes.gestos);
                break;
             case 'ayuda':
                this.navigateTo(window.voiceConfig.routes.ayuda);
                break;
             case 'miperfil':
                this.navigateTo(window.voiceConfig.routes.miperfil);
                break;
             
            case 'stop':
                this.stop();
                break;
            case 'start':
                this.start();
                break;
            case 'login':
                this.handleLogout();
                break;

             case 'scroll_down':
                this.scrollPage('down'); 
                break;
            case 'scroll_up':
                this.scrollPage('up');   
                break;
            case 'click-select':
                this.simulacionBotonClick('fileLabel', "selecionando imagen");
                break;
            case 'click-subir':
                this.simulacionBotonClick('uploadBtn', "subiendo imagen");
                break;
            case 'click-detectar':
                this.simulacionBotonClick('detectBtn', "inicando detectar personas");
                break;
            case 'click-atras':
                this.simulacionBotonClick('backBtn', "regresando");
                break;
            case 'click-borrar':
                this.simulacionBotonClick('deleteBtn', "borrando");
                break;
            case 'click-next':
                this.simulacionBotonClick('carouselNext', "mostrando siguiente imagen");
                break;
            case 'click-prev':
                this.simulacionBotonClick('carouselPrev', "imagen anterior");
                break;
            default:
                console.warn("Acción no reconocida:", action);
                this.showErrorNotification(`Acción de voz no configurada: "${action}"`);
                break;
        }
    }
    simulacionBotonClick(buttonId, feedbackMessage = "Activando botón...") {
        this.showFeedbackNotification(feedbackMessage);
        const button = document.getElementById(buttonId);
        if (button) {
            button.click(); 
            console.log(`Clic simulado en el botón con ID: ${buttonId}`);
        } else {
            console.warn(`Botón con ID "${buttonId}" no encontrado.`);
            this.showErrorNotification(`Botón "${buttonId}" no encontrado.`);
        }
    }


    navigateTo(url) {
        if (url) {
            window.location.href = url;
        } else {
            console.error("URL de navegación no definida.");
            this.showErrorNotification("No se pudo navegar a la página solicitada.");
        }
    }

    handleLogout() {
    const logoutUrl = window.voiceConfig.actions.logout; 
    if (logoutUrl) {

        const form = document.createElement('form');
        form.action = logoutUrl;
        form.method = 'POST'; 

        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = csrfToken.content;
            form.appendChild(input);
        } else {
            console.warn("Meta tag CSRF no encontrado. El logout POST podría fallar.");
        }
        document.body.appendChild(form);
        form.submit(); 
    } else {
        console.error("URL de logout no definida.");
        this.showErrorNotification("No se pudo realizar el cierre de sesión.");
    }
}

    updateVoiceButtonState(isListening) {
        const voiceBtn = document.getElementById('globalVoiceBtn');
        const searchVoiceBtn = document.getElementById('searchVoiceBtn'); 

        if (voiceBtn) {
            voiceBtn.innerHTML = isListening
                ? '<i class="fas fa-microphone-slash"></i>' 
                : '<i class="fas fa-microphone"></i>';
            
            voiceBtn.classList.toggle('btn-danger', isListening);
            voiceBtn.classList.toggle('btn-primary', !isListening);
        }
        
        if (searchVoiceBtn) {
            searchVoiceBtn.classList.toggle('listening', isListening);
        }
    }

    showFeedbackNotification(message, isError = false) {
        const notification = document.getElementById('voiceFeedback');
        if (!notification) return;

        notification.innerHTML = `
            <i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
            ${message}
        `;
        notification.className = `voice-feedback ${isError ? 'error' : ''}`;
        notification.style.display = 'block';
        
        clearTimeout(this.notificationTimeout);
        this.notificationTimeout = setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    showErrorNotification(message) {
        this.showFeedbackNotification(message, true);
    }
    scrollPage(direction, scrollAmount = window.innerHeight * 0.5) { 
        const currentScrollY = window.scrollY;
        let targetScrollY;

        if (direction === 'down') {
            targetScrollY = currentScrollY + scrollAmount;
            this.showFeedbackNotification("Desplazando hacia abajo...");
        } else if (direction === 'up') {
            targetScrollY = currentScrollY - scrollAmount;
            this.showFeedbackNotification("Desplazando hacia arriba...");
        } else {
            return; 
        }

        
        const maxScrollY = document.documentElement.scrollHeight - window.innerHeight;
        targetScrollY = Math.max(0, Math.min(targetScrollY, maxScrollY));

        window.scrollTo({
            top: targetScrollY,
            behavior: 'smooth' 
        });
    }
}


// Inicialización segura
document.addEventListener('DOMContentLoaded', () => {
    try {
        if (!window.voiceRecognition) {
            window.voiceRecognition = new VoiceRecognition();
        }
    } catch (error) {
        console.error("Error al inicializar la instancia de VoiceRecognition en DOMContentLoaded:", error);
        
    }
});