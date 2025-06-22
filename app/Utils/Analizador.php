<?php
namespace App\Utils;

use Parle\{Lexer, Token, Parser, ParserException};

class Analizador{
    private Parser $parser;
    private Lexer $lexito;

    private string $raiz;

    private int $raizId;
    private string $raizNombre;
    private int $prod_elemento;
    private int $prod_elemento0;
    

    private array $tokens;

    private array $produccionesCapturables = [];

    private array $tokensMap=[];


    public function __construct(){
        $this->parser=new Parser();
        $this->lexito=new Lexer();
        $this->tokens=[];
    }

    public function definirToken(string $nombre, string $regex) {
         if (!isset($this->tokensMap[$nombre])) {
            $id = count($this->tokensMap) + 1; // 
            $this->tokensMap[$nombre] = $id;
        } else {
            $id = $this->tokensMap[$nombre];
        }
        $this->lexito->push($regex, $id);
        $this->parser->token($nombre);

        return $id;
    }

    public function definirProduccion(string $nombre, string $regla): int {
        $id=$this->parser->push($nombre, $regla);
        return $id;
    }

    public function setRaiz(string $nombre,int $id): void {
        $this->raiz = $nombre;
        $this->raizId=$id;
    }

    public function build(): void {
        $this->parser->build();
        $this->lexito->build();
    }

    public function registrarProduccionCapturable(string $nombre, int $id, int $posicion = 0): void {
        $this->produccionesCapturables[] = [
            'nombre' => $nombre,
            'id' => $id,
            'posicion' => $posicion
        ];
    }




    
    
    public function verificarExistencia(string $valor,string $entrada){

       $listaTokens = $this->getTokens($entrada);

        foreach ($listaTokens as $elem) {
            if (strcasecmp($elem, $valor) === 0) {
                return "Elemento '{$valor}' existe";
            }
        }

        return "Elemento '{$valor}' no existe";

    }

    public function getTokens(string $entrada){

         if (!$this->analizar($entrada)) {
            throw new \InvalidArgumentException("Input no válido");
        }

        $this->tokens = [];
        $this->parser->reset($this->raizId);
        $this->lexito->consume($entrada);
        $this->parser->consume($entrada, $this->lexito);

        do {
            if ($this->parser->action === Parser::ACTION_REDUCE) {
                $rid = $this->parser->reduceId;

                foreach ($this->produccionesCapturables as $prod) {
                    if ($rid === $prod['id']) {
                        $this->tokens[] = $this->parser->sigil($prod['posicion']);
                    }
                }
            }

            $this->parser->advance();
        } while ($this->parser->action !== Parser::ACTION_ACCEPT);

        return $this->tokens;
    }


    public function devolverComando(string $entrada){
        $comandos=$this->leerComandos("listaDeComandos.xml");

        $listaTokens=$this->getTokens($entrada);

        $comandosEntrada=[];

        foreach($listaTokens as $tok){
            if(isset($comandos[$tok])){
                $comandosEntrada[$tok]=$comandos[$tok];
            }
        }

        return $comandosEntrada;
    }

    public function emparejarComando(string $nombre,string $accion){
        if (
            !preg_match('/^(Ctrl|Alt)\+[a-zA-Z0-9]+$/', $nombre) &&
            !preg_match('/^[a-zA-Z0-9]+!$/', $nombre)&&
            !preg_match('/^[a-zA-ZáéíóúñÑ]+/', $accion)
        ){
            throw new InvalidArgumentException("El nombre o la accion del comando no cumple con la gramatica");
        }

        $ruta=__DIR__.'/../listaDeComandos.xml';

        if(!file_exists($ruta)){
        $xml = new SimpleXMLElement('<comandos></comandos>');
        }else{
            $xml = simplexml_load_file($ruta);
            if($xml === false){
                throw new Exception("Error al leer el archivo XML.");
            }
        }

        $encontrado = false;
        foreach($xml->comando as $comando){
            if((string)$comando->nombre === $nombre){
                $comando->accion = $accion;
                $encontrado = true;
                break;
            }
        }

        if(!$encontrado){
            $nuevo = $xml->addChild('comando');
            $nuevo->addChild('nombre', $nombre);
            $nuevo->addChild('accion', $accion);
        }

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($ruta);

        return "Comando emparejando ";

    }


    public function analizar(string $entrada){
        try {
        $this->lexito->consume($entrada);
        $this->parser->reset($this->raizId);
        $this->parser->consume($entrada, $this->lexito);

        while(true){
            if ($this->parser->action === Parser::ACTION_ERROR) {
                return false;
            }
            if ($this->parser->action === Parser::ACTION_ACCEPT) {
                break;
            }
            $this->parser->advance();
        }

        return true;
        }catch(\Throwable $e){
            return false;
    }

    }
    private function leerComandos(string $ruta=null){
        if($ruta === null){
            $ruta = __DIR__ . '/../listaDeComandos.xml';
        }

        if(!file_exists($ruta)){
            throw new Exception("Archivo XML no encontrado en $ruta");
        }
        
        $xml=simplexml_load_file($ruta);
        $comandos=[];

        foreach ($xml->comando as $comando) {
            $clave = (string)$comando->nombre;
            $accion = (string)$comando->accion;
            $comandos[$clave] = $accion;
        }

        return $comandos;
    }

}
