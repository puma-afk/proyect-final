class VoiceRecognition {
    constructor() {
        this.recognition = null;
        this.isListening = false;
        this.lastCommandTime = 0;
        this.commandCooldown = 2000;
        this.speechSynth = window.speechSynthesis;
        this.isSpeaking = false; 
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
          
            if (this.isSpeaking) {
                console.log("Ignorando resultado de reconocimiento mientras el sistema habla.");
                return;
            }

            const now = Date.now();
            if (now - this.lastCommandTime < this.commandCooldown) return;

            const lastResultIndex = event.results.length - 1;
            const commandText = event.results[lastResultIndex][0].transcript.trim();

            this.processCommand(commandText);
            this.lastCommandTime = now;
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
            this.speak(errorMessage);
            this.updateVoiceButtonState(false);
            this.isListening = false;
        };

        this.recognition.onend = () => {
            console.log("Reconocimiento de voz terminado.");
           
            if (this.isListening && !this.isSpeaking) {
                console.log("Reconocimiento de voz reiniciado automáticamente.");
                this.recognition.start();
            }
            this.updateVoiceButtonState(this.isListening);
        };

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
                const message = "en que puedo ayudarte";
                this.showFeedbackNotification(message);
                this.speak(message);
                this.updateVoiceButtonState(true);
                this.persistState(true);
                console.log("Reconocimiento de voz iniciado.");
            } catch (e) {
                console.error("Error al iniciar reconocimiento:", e);
                const errorMessage = "No se pudo iniciar el reconocimiento de voz. Verifica permisos ";
                this.showErrorNotification(errorMessage);
                this.speak(errorMessage);
                this.isListening = false;
                this.updateVoiceButtonState(false);
            }
        }
    }

    stop() {
        if (this.recognition && this.isListening) {
            this.recognition.stop();
            this.isListening = false;
            const message = "Micrófono desactivado.";
            this.showFeedbackNotification(message);
            this.speak(message);
            this.updateVoiceButtonState(false);
            this.persistState(false);
            console.log("Reconocimiento de voz detenido.");
        }
    }

    persistState(state) {
        localStorage.setItem('voiceRecognitionActive', state);
    }

    getPersistedState() {
        return localStorage.getItem('voiceRecognitionActive') === 'true';
    }

    processCommand(commandText) {
        const now = Date.now();
        if (now - this.lastCommandTime < this.commandCooldown) {
            return;
        }

        const normalizedCommand = commandText
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/[¿?¡!.,;:]/g, "")
            .trim();

        this.showFeedbackNotification(`Comando reconocido: "${normalizedCommand}"`);
        this.speak(`Comando reconocido: ${normalizedCommand}`); 
        console.log("Comando normalizado para procesar:", normalizedCommand);

        if (!window.voiceConfig || !window.voiceConfig.commands) {
            console.error("Error: window.voiceConfig.commands no está definido.");
            this.showErrorNotification("Error de configuración de comandos de voz.");
            this.speak("Error de configuración de comandos de voz.");
            return;
        }

        let commandFound = false;
        for (const patternString in window.voiceConfig.commands) {
            const normalizedPatternString = patternString.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const phrases = normalizedPatternString.split('|');

            if (phrases.includes(normalizedCommand)) {
                const action = window.voiceConfig.commands[patternString];
                console.log(`Comando "${normalizedCommand}" coincide con acción: "${action}"`);
                this.executeAction(action);
                this.lastCommandTime = now;
                commandFound = true;
                break;
            }
        }

        if (!commandFound) {
            const notRecognizedMessage = `Comando no reconocido: "${normalizedCommand}"`;
            this.showErrorNotification(notRecognizedMessage);
            this.speak(notRecognizedMessage);
            console.warn("No se encontró ninguna coincidencia para el comando:", normalizedCommand);
        }
    }

    executeAction(action) {
        
        if (!window.voiceConfig || !window.voiceConfig.routes || !window.voiceConfig.actions) {
            console.error("Error: Configuración de rutas o acciones de voz no definida.");
            this.showErrorNotification("Error interno de configuración de voz.");
            this.speak("Error interno de configuración de voz.");
            return;
        }

        let feedbackMessage = "";

        switch (action) {
            case 'home':
                feedbackMessage = "Navegando a la página de inicio.";
                this.navigateTo(window.voiceConfig.routes.home);
                break;
            case 'data':
                feedbackMessage = "Navegando a la sección de datos.";
                this.navigateTo(window.voiceConfig.routes.data);
                break;
            case 'module1':
                feedbackMessage = "Navegando al módulo uno.";
                this.navigateTo(window.voiceConfig.routes.module1);
                break;
            case 'vos':
                feedbackMessage = "Navegando a control de voz.";
                this.navigateTo(window.voiceConfig.routes.vos);
                break;
            case 'module4':
                feedbackMessage = "Navegando al módulo cuatro.";
                this.navigateTo(window.voiceConfig.routes.module4);
                break;
            case 'gestos':
                this.simulacionBotonClick('gestureControlBtn', "navegando a control de gestos");
                feedbackMessage = "Navegando a control de gestos.";
                break;
            case 'ayuda':
                feedbackMessage = "Navegando a la página de ayuda.";
                this.navigateTo(window.voiceConfig.routes.ayuda);
                break;
            case 'miperfil':
                feedbackMessage = "Navegando a mi perfil.";
                this.navigateTo(window.voiceConfig.routes.miperfil);
                break;
            case 'stop':
                this.stop();
                feedbackMessage = "Reconocimiento de voz detenido."; 
                break;
            case 'start':
                this.start();
                feedbackMessage = "Reconocimiento de voz iniciado."; 
                break;
            case 'login':
                this.handleLogout();
                feedbackMessage = "Cerrando sesión."; 
                break;
            case 'scroll_down':
                this.scrollPage('down');
                feedbackMessage = "Desplazando hacia abajo."; 
                break;
            case 'scroll_up':
                this.scrollPage('up');
                feedbackMessage = "Desplazando hacia arriba."; 
                break;
            case 'click-select':
                this.simulacionBotonClick('fileLabel', "seleccionando imagen");
                feedbackMessage = "Seleccionando imagen.";
                break;
            case 'click-subir':
                this.simulacionBotonClick('uploadBtn', "subiendo imagen");
                feedbackMessage = "Subiendo imagen.";
                break;
            case 'click-detectar':
                this.simulacionBotonClick('detectBtn', "iniciando detectar personas");
                feedbackMessage = "Iniciando detección de personas.";
                break;
            case 'click-atras':
                this.simulacionBotonClick('backBtn', "regresando");
                feedbackMessage = "Regresando.";
                break;
            case 'click-borrar':
                this.simulacionBotonClick('deleteBtn', "borrando");
                feedbackMessage = "Borrando.";
                break;
            case 'click-next':
                this.simulacionBotonClick('carouselNext', "mostrando siguiente imagen");
                feedbackMessage = "Mostrando siguiente imagen.";
                break;
            case 'click-prev':
                this.simulacionBotonClick('carouselPrev', "imagen anterior");
                feedbackMessage = "Mostrando imagen anterior.";
                break;
            case 'click-gestos':
                this.simulacionBotonClick('gestoStartBtn', "iniciando camara");
                feedbackMessage = "Iniciando cámara para gestos.";
                break;
            case 'click-gestos-d':
                this.simulacionBotonClick('gestoStopBtn', "deteniendo camara");
                feedbackMessage = "Deteniendo cámara para gestos.";
                break;
            case 'click-gestos-vos':
                this.simulacionBotonClick('backToVoiceBtn', "volviendo a control de vos");
                feedbackMessage = "Volviendo a control de voz.";
                break;
            case 'click-comand':
                this.simulacionBotonClick('tabComandos', "mostrar comandos");
                feedbackMessage = "Mostrando comandos.";
                break;
            case 'click-probar':
                this.simulacionBotonClick('tabProbarComandos', "probar comandos");
                feedbackMessage = "Probando comandos.";
                break;
            case 'click-confi':
                this.simulacionBotonClick('tabConfiguracion', "configuración");
                feedbackMessage = "Accediendo a configuración.";
                break;
            case 'click-object':
                this.simulacionBotonClick('selectImagen', "seleccionar imagen");
                feedbackMessage = "Seleccionar imagen para detección de objetos.";
                break;
            case 'click-object-d':
                this.simulacionBotonClick('detectButton', "detectando objetos");
                feedbackMessage = "Detectando objetos.";
                break;
            case 'click-back-o':
                this.simulacionBotonClick('objectbackBtn', "volviendo");
                feedbackMessage = "Volviendo a detección de objetos.";
                break;
            case 'click-borrar-i':
                this.simulacionBotonClick('objectdeleteBtn', "borrando");
                feedbackMessage = "Borrando imagen.";
                break;
            default:
                console.warn("Acción no reconocida:", action);
                feedbackMessage = `Acción de voz no configurada: "${action}"`;
                this.showErrorNotification(feedbackMessage);
                break;
        }

        
        if (feedbackMessage && !['stop', 'start', 'login', 'scroll_down', 'scroll_up'].includes(action)) {
            this.speak(feedbackMessage);
        }
    }

    simulacionBotonClick(buttonId, feedbackMessage = "Activando botón...") {
        this.showFeedbackNotification(feedbackMessage);
        this.speak(feedbackMessage);
        const button = document.getElementById(buttonId);
        if (button) {
            button.click();
            console.log(`Clic simulado en el botón con ID: ${buttonId}`);
        } else {
            console.warn(`Botón con ID "${buttonId}" no encontrado.`);
            const errorMessage = `Botón "${buttonId}" no encontrado.`;
            this.showErrorNotification(errorMessage);
            this.speak(errorMessage);
        }
    }


    navigateTo(url) {
        if (url) {
            window.location.href = url;
            this.speak(`Navegando a ${url.split('/').pop().replace('.html', '').replace('-', ' ')}`);
        } else {
            console.error("URL de navegación no definida.");
            const errorMessage = "No se pudo navegar a la página solicitada.";
            this.showErrorNotification(errorMessage);
            this.speak(errorMessage);
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
            this.speak("Cierre de sesión exitoso.");
        } else {
            console.error("URL de logout no definida.");
            const errorMessage = "No se pudo realizar el cierre de sesión.";
            this.showErrorNotification(errorMessage);
            this.speak(errorMessage);
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
            this.speak("Desplazando hacia abajo.");
        } else if (direction === 'up') {
            targetScrollY = currentScrollY - scrollAmount;
            this.showFeedbackNotification("Desplazando hacia arriba...");
            this.speak("Desplazando hacia arriba.");
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
    
    speak(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = this.recognition.lang;
            utterance.rate = 1.0;
            utterance.pitch = 1.0;

            const voices = this.speechSynth.getVoices();
            const esVoice = voices.find(voice => voice.lang === 'es-ES' || voice.lang === 'es_ES');
            if (esVoice) {
                utterance.voice = esVoice;
            }

           
            this.isSpeaking = true;

            if (this.isListening) {
                this.recognition.stop();
                console.log("Reconocimiento detenido temporalmente para la síntesis de voz.");
            }

            utterance.onend = () => {
                this.isSpeaking = false; 
                console.log("Síntesis de voz terminada.");
               
                if (this.isListening) {
                    
                    setTimeout(() => {
                        if (this.isListening) { 
                            this.recognition.start();
                            console.log("Reconocimiento reiniciado después de la síntesis de voz.");
                        }
                    }, 200); 
                }
            };

            utterance.onerror = (event) => {
                console.error("Error en la síntesis de voz:", event);
                this.isSpeaking = false; 
                if (this.isListening) {
                    this.recognition.start(); 
                }
            };

            this.speechSynth.cancel(); 
            this.speechSynth.speak(utterance);
        } else {
            console.warn("API de Text-to-Speech no soportada por este navegador.");
        }
    }
}


document.addEventListener('DOMContentLoaded', () => {
    try {
        if (!window.voiceRecognition) {
            window.voiceRecognition = new VoiceRecognition();
        }
    } catch (error) {
        console.error("Error al inicializar la instancia de VoiceRecognition en DOMContentLoaded:", error);
    }
});