<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\PriorityQueue;
use SimpleXMLElement;
use DOMDocument;

class ColaController extends Controller
{
    public function procesar(){
        $colaJSON=storage_path("app/cola.json");
        $cola= new PriorityQueue('crc32b',$colaJSON);
        $total=0;

        $historialRuta=storage_path("app/colaProcesada.xml");

        try{
            if(!file_exists($historialRuta) || filesize($historialRuta) === 0){
                
                $xml= new SimpleXMLElement('<IMAGENESPROCESADAS></IMAGENESPROCESADAS>');
                
            }else{
                $xml=simplexml_load_file($historialRuta);
            }
        }catch(\Exception $e){
            return back()->withErrors(['error' => 'El documento para guardar datos de las imagenes procesadas no se pudo cargar/crear']);
        }

        $i=0;

        while(!$cola->isEmpty()){
            $imagen =$cola->pop(); 

            $entrada = $imagen['entrada'];
            $salida  = $imagen['salida'];
            //REVISAR
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
            $i++;
            $total += $personas_detectadas;

            $nuevo=$xml->addChild('DatosDelResultado');
            $nuevo->addChild('Nro',$i);
            $nombreArchivo = basename($salida);
            $nuevo->addChild('imagen',$nombreArchivo);
            $nuevo->addChild('PersonasDetectadas',$personas_detectadas); 
            
        }

         $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());
            $dom->save($historialRuta);


            $imagenesProcesadas = [];
            if (file_exists($historialRuta)) {
                $xml = simplexml_load_file($historialRuta);
                foreach ($xml->DatosDelResultado as $dato) {
                    $imagenesProcesadas[] = [
                        'imagen' => (string) $dato->imagen,
                        'personas_detectadas' => (int) $dato->PersonasDetectadas,
                        'numero' => (int) $dato->Nro
                    ];
                }
            }

        session()->flash('imagenesProcesadas', $imagenesProcesadas);
        return redirect()->route('modulo1')->with('cantidad', $total);

    }

    public function borrar(){
        $imagenesPath =storage_path("app/public/imagenes");
        $deteccionesPath =storage_path("app/public/detecciones");
        $colaPath = storage_path("app/cola.json");
        $colaProcesadaPath =storage_path("app/colaProcesada.xml");

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
            file_put_contents($colaProcesadaPath, '<IMAGENESPROCESADAS></IMAGENESPROCESADAS>');
        }

        return redirect()->route('modulo1')->with('mensaje', 'Se borraron todas las imágenes, detecciones y colas.');
    }
}
