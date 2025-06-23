<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    


    <title>Inicio - Plantilla Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dark-bg: #121212;
            --darker-bg: #0a0a0a;
            --card-bg: #1e1e1e;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --accent-blue: #4285f4;
            --accent-blue-light: #5d9bff;
            --border-color: #333;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        a {
            color: var(--accent-blue-light);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        a:hover {
            color: var(--accent-blue);
            text-decoration: underline;
        }
        
        .navbar {
            background-color: var(--darker-bg) !important;
            border-bottom: 2px solid var(--accent-blue);
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--accent-blue) !important;
        }
        
        .nav-link {
            color: var(--text-primary) !important;
            transition: all 0.3s;
            position: relative;
            padding-bottom: 5px;
        }
        
        .nav-link:hover, .nav-link:focus {
            color: var(--accent-blue) !important;
        }
        
        .nav-link:hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--accent-blue);
        }
        
        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
        }
        
        .dropdown-item {
            color: var(--text-primary);
        }
        
        .dropdown-item:hover {
            background-color: var(--accent-blue);
            color: white;
        }

        /* Estilos para el carrusel */
        .carousel {
            border-bottom: 3px solid var(--accent-blue);
            max-height: 400px;
            overflow: hidden;
             margin-bottom: 20px;
        }

        .carousel-item {
            height: 400px;
            transition: transform 1s ease-in-out;
        }

        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            padding: 20px;
            bottom: 40px;
        }

        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
            background-color: var(--text-secondary);
            border: none;
        }

        .carousel-indicators .active {
            background-color: var(--accent-blue);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: var(--accent-blue);
            border-radius: 50%;
            padding: 15px;
            background-size: 60%;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://via.placeholder.com/1920x600/121212/4285f4?text=Plantilla+Azul');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
            border-bottom: 3px solid var(--accent-blue);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--accent-blue);
        }
        
        .feature-card, .news-card, .profile-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            border-top: 3px solid var(--accent-blue);
            color: var(--text-primary);
        }
        
        .feature-card:hover, .news-card:hover, .profile-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(66, 133, 244, 0.2);
            border-color: var(--accent-blue);
        }
        
        .btn-primary {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-blue-light);
            border-color: var(--accent-blue-light);
        }
        
        .btn-outline-light {
            color: var(--text-primary);
            border-color: var(--text-primary);
        }
        
        .btn-outline-light:hover {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
        }
        
        .profile-card .card-header {
            background-color: var(--accent-blue);
            color: white;
            border-bottom: 0;
            font-weight: 600;
        }
        
        .profile-card .card-body {
            padding: 2rem;
            background-color: var(--card-bg);
        }
        
        .profile-card img {
            border: 4px solid var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.3);
        }
        
        .badge.bg-success {
            background-color: var(--accent-blue) !important;
        }
        
        footer {
            background-color: var(--darker-bg);
            color: var(--text-secondary);
            padding: 40px 0;
            margin-top: 60px;
            border-top: 2px solid var(--accent-blue);
        }
        
        footer a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        footer a:hover {
            color: var(--accent-blue);
            text-decoration: none;
        }
        
        footer h5 {
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .social-icons a {
            color: var(--text-secondary);
            margin: 0 10px;
            font-size: 1.5rem;
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: var(--accent-blue);
        }
        
        .text-muted {
            color: var(--text-secondary) !important;
        }
        
        /* Iconos con referencia al texto */
        .icon-text {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .icon-text i {
            margin-right: 10px;
            color: var(--accent-blue);
            font-size: 1.2rem;
            min-width: 25px;
            text-align: center;
        }
        
        /* Efectos especiales */
        .text-glow {
            transition: text-shadow 0.3s;
        }
        
        .text-glow:hover {
            text-shadow: 0 0 10px rgba(66, 133, 244, 0.7);
        }
        
        /* Franjas azules decorativas */
        .blue-stripe {
            height: 5px;
            background: linear-gradient(90deg, var(--dark-bg), var(--accent-blue), var(--dark-bg));
            margin: 30px 0;
            border-radius: 5px;
        }
        
        /* Mejor visibilidad */
        .form-control {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }
        
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: var(--text-primary) !important;
            border-color: var(--accent-blue) !important;
            box-shadow: 0 0 0 0.25rem rgba(66, 133, 244, 0.25);
        }
        
        ::placeholder {
            color: var(--text-secondary) !important;
            opacity: 1;
        }

        /* Estilo para botones modificados */
        .boton {
            display: inline-block;
            font-weight: 500;
            color: var(--text-primary);
            text-align: center;
            vertical-align: middle;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            background-color: var(--accent-blue);
            border: 1px solid var(--accent-blue);
            transition: all 0.3s;
            text-decoration: none;
        }

        .boton:hover {
            background-color: var(--accent-blue-light);
            border-color: var(--accent-blue-light);
            color: white;
            text-decoration: none;
        }

        .dropdown-item .btn-primary {
            width: 100%;
            text-align: left;
            background-color: transparent;
            border: none;
            color: var(--text-primary);
            padding: 0.25rem 1.5rem;
        }

        .dropdown-item .btn-primary:hover {
            background-color: var(--accent-blue);
            color: white;
        }
        #voiceSearchBtn.listening {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
          0% {
               box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
          70% {
               box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
              }
          100% {
               box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* Estilos para los botones de título */
        .title-button {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .title-button:hover {
            color: var(--accent-blue-light);
            background-color: rgba(66, 133, 244, 0.1);
        }

        .title-button i {
            margin-right: 10px;
        }
    </style>
    
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand text-glow" href="#" id="brandLogo">
                <i class="fas fa-cube me-2"></i>PLANTILLA
            </a>
            <button class="navbar-toggler" type="button" id="navbarToggler" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#" id="homeLink">
                            <i class="fas fa-home me-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="servicesLink">
                            <i class="fas fa-box me-1"></i> Servicios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('nombres') }}" class="nav-link boton" id="dataLink">
                            <i class="fas fa-database me-1"></i> Datos
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="moreOptionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bars me-1"></i> Más Opciones
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMas">
                         <li><a class="dropdown-item" href="{{ route('modulo1')}}"><i class="fas fa-images me-2"></i>Modulo 1 Personas</a></li>
                         <li><a class="dropdown-item" href="{{ route('modulo2') }}"><i class="fas fa-newspaper me-2"></i> Modulo 2 Voz</a></li>
                        <li><a class="dropdown-item" href="{{ route('modulo4') }}"><i class="fas fa-images me-2"></i> Modulo 4 Objetos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('operacion2') }}" id="helpLink"><i class="fas fa-images me-2"></i> Ayuda</a></li>
                        </ul>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <form class="d-flex me-3" role="search" id="searchForm">
                      <div class="input-group input-group-sm">
                       <input class="form-control" type="search" id="searchInput" placeholder="Buscar..." aria-label="Search">
                       <button class="btn btn-outline-light" type="submit" id="searchButton">
                        <i class="fas fa-search"></i>
                       </button>
                       <button class="btn btn-outline-light" type="button" id="voiceSearchBtn" 
                         onclick="window.voiceRecognition.start(); document.getElementById('searchInput').focus()">
                         <i class="fas fa-microphone"></i>
                       </button>
                      </div>
                    </form>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('images/ejemplo.jpg') }}" alt="Foto perfil" class="rounded-circle me-1" width="30" id="userImage">
                                {{ $username ?? 'Usuario' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a href="{{ route('informacion') ?? '#' }}" class="dropdown-item" id="profileLink">
                                    <i class="fas fa-info-circle me-2"></i> Mi perfil
                                </a></li>
                                <li><a class="dropdown-item" href="#" id="settingsLink"><i class="fas fa-cogs me-2"></i> Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                 <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                    @csrf
                                    <button type="submit" class="dropdown-item" id="logoutButton">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                    </button>
                                 </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="blue-stripe"></div>

    <!-- Carrusel de imágenes -->
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" id="carouselIndicator1" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" id="carouselIndicator2" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" id="carouselIndicator3" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/carousel/imagen1.jpg') }}" class="d-block w-100" alt="Slide 1" id="carouselImage1">
                <div class="carousel-caption d-none d-md-block">
                    <button onclick="window.location.href='{{ route('modulo1') }}'" class="title-button" id="carouselTitle1">
                        <i class="fas fa-camera"></i> modulo 1
                    </button>
                    <p> reconocimiento de personas dentro de una foto </p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carousel/imagen2.jpg') }}" class="d-block w-100" alt="Slide 2" id="carouselImage2">
                <div class="carousel-caption d-none d-md-block">
                    <button onclick="window.location.href='{{ route('modulo2') }}'" class="title-button" id="carouselTitle2">
                        <i class="fas fa-microphone"></i> modulo 2
                    </button>
                    <p>admistracion de comandos de vos </p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/carousel/imagen3.jpg') }}" class="d-block w-100" alt="Slide 3" id="carouselImage3">
                <div class="carousel-caption d-none d-md-block">
                    <button onclick="window.location.href='{{ route('operacion3') }}'" class="title-button" id="carouselTitle3">
                        <i class="fas fa-chart-bar"></i> titulo
                    </button>
                    <p>Descripción </p>
                </div>
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" id="carouselPrev" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" id="carouselNext" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <div class="blue-stripe"></div>

    <section class="container my-5 py-4">
        <h2 class="text-center mb-5 display-5 fw-bold section-title">funcionalidades</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-card card h-100 p-3">
                    <div class="card-body">
                        <button onclick="window.location.href='{{ route('modulo1') }}'" class="icon-text justify-content-center title-button" id="featureButton1">
                            <i class="fas fa-check-circle"></i>
                            <span>TITULO</span>
                        </button>
                        <p class="card-text mt-3">Descripción .</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card card h-100 p-3">
                    <div class="card-body">
                        <button onclick="window.location.href='{{ route('modulo2') }}'" class="icon-text justify-content-center title-button" id="featureButton2">
                            <i class="fas fa-handshake"></i>
                            <span>TITULO</span>
                        </button>
                        <p class="card-text mt-3">Explicación </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card card h-100 p-3">
                    <div class="card-body">
                        <button onclick="window.location.href='{{ route('operacion3') }}'" class="icon-text justify-content-center title-button" id="featureButton3">
                            <i class="fas fa-lightbulb"></i>
                            <span>TITULO</span>
                        </button>
                        <p class="card-text mt-3">Información </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="blue-stripe"></div>

    <section class="container my-5 py-4"> 
        <div class="row justify-content-center align-items-center"> 
            <div class="col-lg-8">
                <div class="row align-items-center"> 
                    <div class="col-md-4 text-center mb-3 mb-md-0">
                        <img src="{{ asset('images/ejemplo.jpg') }}" alt="Imagen del usuario" id="profileImage" class="img-fluid rounded-circle mb-3" width="150" style="border: 4px solid #007bff;">
                        <h4 class="mb-1" id="usernameDisplay">{{ $username ?? 'Nombre de Usuario' }}</h4>
                        <span class="badge bg-success" id="userStatus">{{ $estado ?? 'Activo' }}</span>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <p class="mb-1"><strong><i class="fas fa-clock me-2"></i>Hora Actual:</strong> <span id="currentTime">{{ date('H:i') }}</span></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1"><strong><i class="fas fa-calendar-alt me-2"></i>Fecha Actual:</strong> <span id="currentDate">{{ date('d/m/Y') }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="blue-stripe"></div>
   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const myCarousel = new bootstrap.Carousel('#mainCarousel', {
                interval: 10000,
                ride: 'carousel'
            });

        
            setInterval(updateTime, 60000);
            
            function updateTime() {
                const now = new Date();
                document.getElementById('currentTime').textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                document.getElementById('currentDate').textContent = now.toLocaleDateString();
            }
        });
    </script>
   @include('vistas-globales.vos-iu')
@include('vistas-globales.vos-comandos') 
<script src="{{ asset('voiceRecognition.js') }}"></script>

</body>
</html>