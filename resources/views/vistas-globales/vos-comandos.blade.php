<script>

window.voiceConfig = {
    // Rutas principales
    routes: {
        home: "{{ route('perfil') }}",
        data: "{{ route('nombres') }}",
        module1: "{{ route('modulo1') }}",
        vos: "{{ route('modulo2') }}",
        estadisticas: "{{ route('operacion3') }}",
        gestos: "{{ route('operacion1') }}",
        ayuda: "{{ route('operacion2') }}",
        miperfil: "{{ route('informacion') }}"
    },

    // Comandos de voz y sus acciones asociadas
    commands: {
        'ir a inicio|volver|inicio': 'home',
        'ir a datos|ver datos|mis datos': 'data',
        'modulo 1|primera lección|modulo uno': 'module1',
        'modulo 2|segunda lección|modulo dos': 'vos',
        'modulo dos gestos|ir a gestos|gestos': 'gestos',
        'ver estadisticas|ir a estadisticas|estadisticas': 'estadisticas',
        'ayuda|informacion de ayuda|mostrar ayuda': 'ayuda',
        'ver mi perfil|ir a perfil|perfil': 'miperfil',
        'detener|desactivar micrófono|silenciar': 'stop',
        'activar|empezar a escuchar|escuchar': 'start',
        'cerrar sesión|salir del sistema|logout': 'logout',
         //comnados de accion
        'bajar|desplazar abajo|scroll abajo': 'scroll_down', 
        'subir|desplazar arriba|scroll arriba': 'scroll_up',
         'subir archivo|cargar archivo': 'upload_file',      
        'iniciar camara|abrir camara': 'start_camera',      
        'iniciar reconocimiento|activar microfono': 'start_voice_recognition', 
        'detener voz|desactivar microfono': 'stop_voice_recognition', 
        // Agrega más comandos
        'guardar cambios|guardar': 'save_changes',          
        'enviar formulario|enviar': 'submit_form'         
           
    },
      
    
    actions: {
    'logout': "{{ route('logout') }}"
    }

   
};
</script>