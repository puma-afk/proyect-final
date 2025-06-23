<script>
window.voiceConfig = {
    routes: {
        home: "{{ route('perfil') }}",
        back: "{{ url()->previous() }}",
        next: "{{ route('modulo1') }}",
        help: "{{ route('operacion2') }}"
    },
    actions: {
        logout: "{{ route('logout') }}"
    }
};


window.gestureSettings = {
    thumbsUpThreshold: 0.15,
    fistThreshold: 0.1,
    pointThreshold: 0.2
};
</script>