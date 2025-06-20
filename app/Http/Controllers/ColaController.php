<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\PriorityQueue;

class ColaController extends Controller
{
    public function procesar(){
        $colaJSON=storage_path("app/cola.json");
        $cola= new PriorityQueue('crc32b',$colaJSON);
        $total=0;

        $historialPath=storage_path("app/colaProcesada.json");

        $historial=file_exists($historialPath) ? json_decode(file_get_contents($historialPath), true) : [];

        while(!$cola->isEmpty()){
            $imagen =$cola->pop(); 

            $entrada = $imagen['entrada'];
            $salida  = $imagen['salida'];

            $python="C:\\Users\\jhosb\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";

            $script=base_path("Python/reconocedor.py");
            $comando = "$python " .escapeshellarg($script).' '. escapeshellarg($entrada) . ' ' . escapeshellarg($salida);
            $comando .= ' 2>&1';
            $resultado = shell_exec($comando);
            
            $datos = json_decode($resultado, true);
            if(!is_array($datos) || !isset($datos['personas_detectadas'])) {
                $personas_detectadas = 0;
            } else {
                $personas_detectadas = intval($datos['personas_detectadas']);
            }
            $total += $personas_detectadas;

            $historial[]= [
                'imagen' => $salida,
                'personas_detectadas' => $personas_detectadas,
            ];
        }

        file_put_contents($historialPath, json_encode($historial, JSON_PRETTY_PRINT));

        return redirect()->route('modulo1')->with('cantidad',$total);
    }

    public function borrar(){
        $imagenesPath = storage_path('app/public/imagenes');
        $deteccionesPath = storage_path('app/public/detecciones');
        $colaPath = storage_path('app/cola.json');
        $colaProcesadaPath = storage_path('app/colaProcesada.json');

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

        $vaciarCarpeta($imagenesPath);
        $vaciarCarpeta($deteccionesPath);

        
        if (file_exists($colaPath)) {
            file_put_contents($colaPath, json_encode([
                'queue' => [],
                'num_elements' => 0,
                'hashmap' => []
            ], JSON_PRETTY_PRINT));
        }
                
        if (file_exists($colaProcesadaPath)) {
            file_put_contents($colaProcesadaPath, json_encode([], JSON_PRETTY_PRINT));
        }

        return redirect()->route('modulo1')->with('mensaje', 'Se borraron todas las imágenes, detecciones y colas.');
    }
}
