<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --error: #ef4444;
            --text: #e5e7eb;
            --bg: #111827;
            --input-bg: #1f2937;
            --border: #374151;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 1rem;
            line-height: 1.5;
        }
        
        .login-container {
            width: 100%;
            max-width: 380px;
            background: var(--input-bg);
            padding: 2.5rem 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        h1 {
            font-size: 1.25rem;
            font-weight: 500;
            text-align: center;
            margin-bottom: 1.75rem;
            color: var(--text);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text);
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.6rem;
            color: #6b7280;
        }
        
        input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            background-color: var(--bg);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: var(--text);
            transition: all 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        }
        
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 0.5rem;
        }
        
        button:hover {
            background-color: var(--primary-hover);
        }
        
        .error {
            color: var(--error);
            font-size: 0.875rem;
            text-align: center;
            margin: 1.25rem 0;
            padding: 0.75rem;
            background-color: rgba(239, 68, 68, 0.1);
            border-radius: 0.375rem;
        }
        
        .footer {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.8125rem;
            color: #9ca3af;
        }
        
        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-lock"></i> Acceso
        </div>
        <h1>Ingresa a tu cuenta</h1>
        
        @if($errors->any())
            <div class="error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="username">Usuario</label>
                <i class="fas fa-user input-icon"></i>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    autofocus
                    placeholder="nombre.usuario"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <i class="fas fa-key input-icon"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="••••••••"
                >
            </div>
            
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Continuar
            </button>
        </form>
        
    
    </div>
</body>
</html>