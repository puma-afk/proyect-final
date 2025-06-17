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
            $personas_detectadas = intval($datos['personas_detectadas'] ?? 0);

            $total += $personas_detectadas;
            
        }

        return redirect()->route('modulo1')->with('cantidad',$total);
    }
}
