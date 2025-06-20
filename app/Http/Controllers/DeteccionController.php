<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\PriorityQueue;

class DeteccionController extends Controller
{
    public function detectar (Request $request){
        $elemento=null;
        try{
            //VALIDA IMAGEN SUBIDA
            $imagen = $request->file('imagen');
                if (!$imagen) {
                    return back()->withErrors(['No se recibió ninguna imagen.']);
                }
            //MUEVE LAS IMAGENES

            $imagenesExistentes = glob(storage_path("app/public/imagenes/imagen*.jpg"));
            $numero = count($imagenesExistentes) + 1; 
            
            $timestamp = now()->format('Ymd_His');
            $nombreBase = 'imagen_' . $timestamp . '_' . uniqid();
            $extensionImagen=$imagen->getClientOriginalExtension();

            $nombreCompleto="$nombreBase.$extensionImagen";

            $rutaLocal = storage_path("app/public/imagenes/$nombreCompleto");
            $imagen->move(storage_path("app/public/imagenes"), $nombreCompleto);

            if (!file_exists($rutaLocal)) {
                return back()->withErrors(['error' => 'No se pudo guardar la imagen en disco.']);
            }

            $nombreProcesada=$nombreBase.'_Procesada.'.$extensionImagen;
            $rutaDetecciones= storage_path("app/public/detecciones/$nombreProcesada");

            //CREA EL ELEMENTO PARA LA COLA
            $elemento=[
                'entrada'=>$rutaLocal,
                'salida'=>$rutaDetecciones
            ];

        }catch(\Exception $e){
            return back()->withErrors(['error'=>'Error al guardar imagen: '.$e->getMessage()]);
        }
        
        //VERIFICAMOS Y ENCOLAMOS
        try{
            $priorityQueue= new PriorityQueue();
            if(($priorityQueue->count())>10){
                return back()->withErrors(['error' => 'La cola ya tiene 10 imágenes.']);
            }

            $priorityQueue->push($elemento,time());
        }catch(\Exception $e){
            return back()->withErrors(['error'=>'Error al encolar imagen: '.$e->getMessage()]);
        }

        return back()->with('exito','Imagen lista para procesar');
        
    }
}
