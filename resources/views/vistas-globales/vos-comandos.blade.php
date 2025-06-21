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
        'cerrar sesión|salir del sistema|': 'login',
         //comnados de accion
        'bajar|desplazar abajo|scroll abajo': 'scroll_down', 
        'subir|desplazar arriba|scroll arriba': 'scroll_up',
        // comandos  para botones
        'seleccionar imagen|seleccionar': 'click-select', 
        'subir imagen|cargar imagen': 'click-subir',     
        'detectar personas|detectar': 'click-detectar',      
        'atras|salir de modulo 1': 'click-atras', 
        'borrar todo|borrar': 'click-borrar', 
        // comandos para cambiar imagen
        'siguiente imagen|siguiente': 'click-next', 
        'anterior imagen|anterior': 'click-prev'       
           
    },
      
    
    actions: {
    'logout': "{{ route('logout') }}"
    }

   
};
</script>