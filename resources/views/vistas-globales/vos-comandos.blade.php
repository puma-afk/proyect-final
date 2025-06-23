<script>

window.voiceConfig = {
    // Rutas principales
    routes: {
        home: "{{ route('perfil') }}",
        data: "{{ route('nombres') }}",
        module1: "{{ route('modulo1') }}",
        vos: "{{ route('modulo2') }}",
        module4: "{{ route('modulo4') }}",
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
        'control de gestos|ir a gestos|gestos': 'gestos',
        'modulo 4|modulo cuatro': 'module4',
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
        'iniciar deteccion| dectectar gestos': 'click-gestos', 
        'detener camara|detener gestos': 'click-gestos-d', 
        'volver a control de vos|volver a vos': 'click-gestos-vos', 
        'comandos': 'click-comand', 
        'probar comandos': 'click-probar',
        'configuracion': 'click-confi',
        'selecionar imagen objeto|selecionar objeto': 'click-object',
        'detectar objeto|detectar objetos': 'click-object-d',
        'atras|volver a inicio': 'click-back-o',
        'borrar imagen|borrar': 'click-borrar-i',
        // comandos para cambiar imagen
        'siguiente imagen|siguiente': 'click-next', 
        'anterior imagen|anterior': 'click-prev'       
           
    },
      
    
    actions: {
    'logout': "{{ route('logout') }}"
    }

   
};
</script>