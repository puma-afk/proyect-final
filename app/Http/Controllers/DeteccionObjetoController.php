<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeteccionObjetoService;

class DeteccionObjetoController extends Controller{
    protected $servicio;

    public function __construct(DeteccionObjetoService $servicio){
        $this->servicio = $servicio;
    }

    public function detectar(Request $request){
        $resultado = $this->servicio->procesar($request);

        if ($resultado['exito']) {
            return back()->with([
                'mensaje' => $resultado['mensaje'],
                'ruta_procesada' => $resultado['ruta_procesada'],
                'objetos_detectados' => $resultado['objetos_detectados']
            ]);
        } else {
            return back()->withErrors(['error' => $resultado['mensaje']]);
        }
    }

    public function borrar(){
        $imagenesObjetos=storage_path("app/public/imagenesObjetos");
        $objetosDetectados=storage_path("app/public/objetosDetectados");

        $vaciarCarpeta = function ($folder) {
            if (is_dir($folder)) {
                $files = glob($folder . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        };

        $vaciarCarpeta($imagenesObjetos);
        $vaciarCarpeta($objetosDetectados);

        return redirect()->route('modulo4')->with('mensaje', 'Se borraron todas las imágenes, detecciones y colas.');

    }
}
