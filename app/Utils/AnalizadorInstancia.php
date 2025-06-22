<?php
namespace App\Utils;
use App\Utils\Analizador;
class AnalizadorInstancia{
    private static ?Analizador $instancia = null;

    public static function obtener(): Analizador {
        if (self::$instancia === null) {
            $analizador = new Analizador;
            $tokenId = $analizador->definirToken('PALABRA','[a-zA-ZáéíóúñÑ]+');
            $prodRaizId = $analizador->definirProduccion('Inicio', 'Elementos');
            $prodElemId = $analizador->definirProduccion('Elementos', 'PALABRA');
            $analizador->registrarProduccionCapturable('Elementos', $prodElemId, 0);
            $analizador->setRaiz('Inicio', $prodRaizId);
            $analizador->build();
            self::$instancia = $analizador;
        }
        return self::$instancia;
    }
}
