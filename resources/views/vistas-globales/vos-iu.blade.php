<!-- Botón flotante de voz y notificaciones -->
<button id="globalVoiceBtn" class="btn btn-primary voice-btn" title="Control por voz (Activar/Desactivar)" aria-label="Control por voz" aria-pressed="false">
    <i class="fas fa-microphone"></i>
</button>

<div id="voiceFeedback" class="voice-feedback" style="display: none;"></div>

<style>
    /* Estilos para el control de voz */
    .voice-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    .voice-feedback {
        position: fixed;
        bottom: 80px;
        right: 20px;
        padding: 12px 16px;
        border-radius: 5px;
        color: white;
        background: #4CAF50;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 9998;
        animation: fadeIn 0.3s;
        max-width: 300px;
        word-wrap: break-word;
    }

    .voice-feedback.error {
        background: #F44336;
    }

    .voice-feedback i {
        margin-right: 8px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    
    #voiceSearchBtn.listening {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>