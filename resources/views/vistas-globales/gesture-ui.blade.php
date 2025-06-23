<button id="gesture-toggle" class="gesture-btn">
    <i class="fas fa-hand-paper gesture-icon"></i>
    <span class="gesture-status">OFF</span>
</button>

<video id="gesture-video" style="display: none;" autoplay playsinline></video>
<div id="gesture-feedback" style="display: none; position: fixed; bottom: 100px; left: 20px; background: #333; color: white; padding: 10px; border-radius: 5px; z-index: 10001;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('gesture-toggle');
    const statusText = toggleBtn.querySelector('.gesture-status');
    const icon = toggleBtn.querySelector('.gesture-icon');
    const feedbackEl = document.getElementById('gesture-feedback');
    const videoEl = document.getElementById('gesture-video');

    // Verificar si el detector está cargado
    if (!window.gestureDetector) {
        console.error("GestureDetector no está disponible");
        feedbackEl.textContent = "Error: Sistema de gestos no cargado";
        feedbackEl.style.display = 'block';
        return;
    }

    toggleBtn.addEventListener('click', async function() {
        try {
            if (window.gestureDetector.isActive) {
                await window.gestureDetector.stop();
                toggleBtn.classList.remove('active');
                statusText.textContent = "OFF";
                icon.className = "fas fa-hand-paper gesture-icon";
                showFeedback("Gestos desactivados");
            } else {
                const started = await window.gestureDetector.start(videoEl);
                if (started) {
                    toggleBtn.classList.add('active');
                    statusText.textContent = "ON";
                    icon.className = "fas fa-hand-rock gesture-icon";
                    showFeedback("Gestos activados");
                }
            }
        } catch (error) {
            console.error("Error al activar gestos:", error);
            showFeedback("Error: " + error.message, true);
        }
    });

    function showFeedback(message, isError = false) {
        feedbackEl.textContent = message;
        feedbackEl.style.background = isError ? '#dc3545' : '#28a745';
        feedbackEl.style.display = 'block';
        setTimeout(() => feedbackEl.style.display = 'none', 2000);
    }
});
</script>

<style>
    .gesture-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #dc3545;
        color: white;
        border: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10000;
        transition: all 0.3s;
    }

    .gesture-btn.active {
        background: #28a745;
    }

    .gesture-icon {
        font-size: 20px;
    }

    .gesture-status {
        font-size: 10px;
        margin-top: 2px;
    }
</style>