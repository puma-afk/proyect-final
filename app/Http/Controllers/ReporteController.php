<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

class ReporteController extends Controller
{
    public function mostrarReportes()
    {
        $ruta = storage_path('app/colaProcesada.xml');
        if (!file_exists($ruta)) {
            return back()->withErrors(['error' => 'El archivo de datos no existe.']);
        }

        $xml = simplexml_load_file($ruta);

        $formas = [];  
        $colores = []; 
        $intervalos = []; 

        foreach ($xml->DatosDelResultado as $resultado) {
            $imagen = (string)$resultado->imagen;
            $personas = (int)$resultado->PersonasDetectadas;

            $formas[$imagen] = $personas;

            
            if ($personas <= 10) {
                $colores['Pocos'] = ($colores['Pocos'] ?? 0) + 1;
            } elseif ($personas <= 20) {
                $colores['Medio'] = ($colores['Medio'] ?? 0) + 1;
            } else {
                $colores['Muchos'] = ($colores['Muchos'] ?? 0) + 1;
            }

            
            $rango = $this->determinarRango($personas);
            $intervalos[$rango] = ($intervalos[$rango] ?? 0) + 1;
        }

        
        $acumulada = [];
        $suma = 0;
        foreach ($intervalos as $rango => $valor) {
            $suma += $valor;
            $acumulada[$rango] = $suma;
        }

        return view('reportes', [
            'formasLabels' => array_keys($formas),
            'formasValores' => array_values($formas),
            'coloresLabels' => array_keys($colores),
            'coloresValores' => array_values($colores),
            'ojivaLabels' => array_keys($acumulada),
            'ojivaValores' => array_values($acumulada),
        ]);
    }

    private function determinarRango(int $personas): string
    {
        if ($personas <= 10) {
            return '1-10';
        } elseif ($personas <= 20) {
            return '11-20';
        } else {
            return '21+';
        }
    }
}

