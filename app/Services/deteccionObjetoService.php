<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Utils\AnalizadorInstancia;


class DeteccionObjetoService
{
    public function procesar(Request $request)
    {
        try {
            $rutaImagen = $this->validarYGuardarImagen($request);
            $objeto = $this->validarYObtenerObjeto($request);
            $label = $this->obtenerLabelObjeto($objeto);
            $resScript = $this->invocarScriptPython($rutaImagen, $label);

            return [
                'exito' => true,
                'mensaje' => 'Objeto detectado correctamente.',
                'ruta_procesada' => $resScript['ruta'],
                'objetos_detectados'=>$resScript['cantidad']
            ];
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function validarYGuardarImagen(Request $request): string{
        
        $imagen = $request->file('imagen');
        if (!$imagen) {
            throw new \Exception('No se recibió ninguna imagen.');
        }

        $nombre = 'objeto_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
        $rutaRelativa = "imagenesObjetos/$nombre";

        $guardado = $imagen->move(storage_path("app/public/imagenesObjetos"), $nombre);
        if (!$guardado) {
            throw new \Exception('No se pudo guardar la imagen');
        }

        session(['imagen_objeto' => $rutaRelativa]);

        return $rutaRelativa;

    }

    private function validarYObtenerObjeto(Request $request): string{
        $entrada = $request->input('objeto');
        if (!$entrada) {
            throw new \Exception('Debe escribir el nombre de un objeto.');
        }

        $analizador = AnalizadorInstancia::obtener();
        if (!$analizador->analizar($entrada)) {
            throw new \Exception('Entrada no válida.');
        }

        $tokens = $analizador->getTokens($entrada);
        if (empty($tokens)) {
            throw new \Exception('No se pudo extraer un objeto de la búsqueda.');
        }

        $objeto = $tokens[0];

        if (!$this->objetoDisponibleEnYolo($objeto)) {
            throw new \Exception('Objeto no disponible para detección.');
        }

        return $objeto;

    }

    private function obtenerLabelObjeto(string $objeto): string{
        $ruta = storage_path("app/objetos_yolo.xml");
        if (!file_exists($ruta)) {
            throw new \Exception("El archivo de objetos YOLO no existe.");
        }

        $xml = simplexml_load_file($ruta);
        foreach ($xml->objeto as $item) {
            if (strcasecmp((string)$item->nombre, $objeto) === 0) {
               return (string)$item->label; 
            }
        }

        throw new \Exception("No se encontró el label del objeto.");
    }

    private function invocarScriptPython(string $rutaImagen, string $label): array {
        $nombreSalida = 'detectado_' . now()->format('Ymd_His') . '_' . uniqid() . '.jpg';
        $carpetaSalida = storage_path("app/public/objetosDetectados");

        if (!file_exists($carpetaSalida)) {
            mkdir($carpetaSalida, 0777, true);
        }

        $rutaSalidaAbsoluta = $carpetaSalida . DIRECTORY_SEPARATOR . $nombreSalida;
        $rutaRelativa = "objetosDetectados/$nombreSalida";

        $rutaScript = "C:\\xampp\\htdocs\\proyect-final\\Python\\detectar_objeto.py";
        $pythonBin = "C:\\Users\\jhosb\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";

        $rutaAbsolutaImagen = storage_path("app/public/" . $rutaImagen);

        $cmd = "{$pythonBin} \"{$rutaScript}\" --image \"{$rutaAbsolutaImagen}\" --label \"{$label}\" --output \"{$rutaSalidaAbsoluta}\" 2>&1";
        $output = shell_exec($cmd);

        file_put_contents(storage_path("logs/deteccion_debug.log"), "CMD: $cmd\nOUTPUT: $output\n", FILE_APPEND);


        if (empty($output)) {
            throw new \Exception("El script no devolvió salida.");
        }

        $lineas = explode("\n", trim($output));
        $ultimaLinea = end($lineas);
        $cantidad = intval(trim($ultimaLinea));

        return [
            'ruta' => $rutaRelativa,
            'cantidad' => $cantidad
        ];
    }



    private function objetoDisponibleEnYolo(string $objeto): bool{
        $ruta = storage_path('app/objetos_yolo.xml');
        if (!file_exists($ruta)) {
            throw new \Exception("El archivo de objetos YOLO no existe.");
        }

        $xml = simplexml_load_file($ruta);
        foreach ($xml->objeto as $item) {
            if (strcasecmp((string)$item->nombre, $objeto) === 0) {
                return true;
            }
        }
        return false;
    }
}
