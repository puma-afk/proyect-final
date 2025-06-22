<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\VoiceGestureController;
use App\Http\Controllers\DeteccionObjetoController;

Route::get('/', function () {
    return view('login');
})->name('login.form');

Route::post('/login', function (Request $request) {
    
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
// comandos de vos
Route::get('/voice-routes', function() {
    return response()->json([
        'home' => route('perfil'),
        'datos' => route('nombres'),
        'modulo1' => route('modulo1'),
        'modulo2' => route('modulo2')
    ]);
});


Route::post('/api/voice-gesture-command', [VoiceGestureController::class, 'processCommand']);


Route::get('/modulo4',function(){
    return view('modulo 4 Objetos');
})->name('modulo4');

Route::post('/deteccion_objeto',['App\Http\Controllers\DeteccionObjetoController'::class,'detectar'])->name('detectar.objeto');

Route::get('/objetos-detectados/{filename}', function ($filename) {
    $path = storage_path('app/public/images/objetosDetectados/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('ver.objeto.detectado');

Route::post('/borrarObjetos',['App\Http\Controllers\DeteccionObjetoController'::class,'borrar'])->name('borrar.objetos');




 