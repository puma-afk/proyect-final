<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\VoiceGestureController;

Route::get('/', function () {
    return view('login');
})->name('login.form');

Route::post('/login', function (Request $request) {
    // Validación básica
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string'
    ]);

   
    Session::put([
        'username' => $request->username,
        'email' => $request->username.'@example.com', 
        'telefono' => '+591 '.rand(10000000, 99999999), 
        'estado' => 'Activo'
    ]);

    return redirect()->route('perfil');
})->name('login');

Route::get('/perfil', function () {
    // Verificar si hay datos en sesión
    if (!Session::has('username')) {
        return redirect()->route('login.form');
    }

    return view('perfil', [
        'username' => Session::get('username'),
        'email' => Session::get('email'),
        'telefono' => Session::get('telefono'),
        'estado' => Session::get('estado')
    ]);
})->name('perfil');

Route::get('/informacion', function () {
    // Verificar si hay datos en sesión
    if (!Session::has('username')) {
        return redirect()->route('login.form');
    }

    return view('informacion', [
        'username' => Session::get('username'),
        'email' => Session::get('email'),
        'telefono' => Session::get('telefono'),
        'estado' => Session::get('estado')
    ]);
})->name('informacion');

Route::post('/logout', function () {
    Session::flush();
    return redirect()->route('login.form');
})->name('logout');

Route::get('/nombres', function () {
    return view('nombres'); 
})->name('nombres');

Route::get('/modulo1', function (){
    return view('modulo 1 Captura');
})->name('modulo1');

Route::post('/subirImagen',['App\Http\Controllers\DeteccionController'::class,'detectar'])->name('deteccionController');

Route::post('/detectarCola',['App\Http\Controllers\ColaController'::class,'procesar'])->name('detectar.humanos');

Route::post('/borrarTodo',['App\Http\Controllers\ColaController'::class,'borrar'])->name('borrar.todo');

Route::get('/operacion1', function () {
    return view('operacion1');
})->name('operacion1');

Route::get('/operacion2', function () {
    return view('operacion2');
})->name('operacion2');

Route::get('/operacion3', function () {
    return view('operacion3');
})->name('operacion3');

//modulo2
Route::get('/modulo2', function () {
    return view('modulo2');
})->name('modulo2');

Route::get('/perfil', function () {
    return view('perfil'); 
})->name('perfil'); 


Route::post('/api/voice-gesture-command', [VoiceGestureController::class, 'processCommand']);


// ===== routes/web.php =====


// Ruta principal para mostrar la página de control
Route::get('/voice-gesture-control', [VoiceGestureController::class, 'index'])
    ->name('voice.gesture.control');

// Ruta para obtener configuración del sistema
Route::get('/voice-gesture-config', [VoiceGestureController::class, 'getSystemConfig'])
    ->name('voice.gesture.config');

// Ruta para estadísticas (opcional, requiere autenticación)
Route::get('/voice-gesture-stats', [VoiceGestureController::class, 'getCommandStats'])
    ->middleware('auth')
    ->name('voice.gesture.stats');


// ===== routes/api.php =====



// API principal para recibir comandos de voz y gestos
Route::post('/voice-gesture-command', [VoiceGestureController::class, 'processCommand'])
    ->name('api.voice.gesture.command');

// API para obtener configuración (sin autenticación)
Route::get('/voice-gesture-config', [VoiceGestureController::class, 'getSystemConfig'])
    ->name('api.voice.gesture.config');

// APIs adicionales con autenticación (opcional)
Route::middleware('auth:sanctum')->group(function () {
    
    // Estadísticas de comandos del usuario
    Route::get('/voice-gesture-stats', [VoiceGestureController::class, 'getCommandStats']);
    
    // Historial de comandos del usuario
    Route::get('/voice-gesture-history', function() {
        // Implementar si necesitas historial
        return response()->json(['message' => 'Historial de comandos']);
    });
    
});

 