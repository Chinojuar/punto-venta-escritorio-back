<?php

namespace App\Respuestas;

class Respuestas
{
    public static function respuesta200($mensaje, $resultado)
    {
        return $respuesta = [
            'codigo' => 200,
            'mensaje' => $mensaje,
            'payload' => $resultado,
        ];
    }

    public static function respuesta200NoResultados($mensaje)
    {
        return $respuesta = [
            'codigo' => 200,
            'mensaje' => $mensaje,
        ];
    }

    public static function respuesta400($error)
    {
        return $respuesta = [
            'codigo' => 400,
            'mensaje' => 'Mala solicitud.',
            'error' => $error,
        ];
    }

    public static function respuesta401($error)
    {
        return $respuesta = [
            'codigo' => 401,
            'mensaje' => 'No autorizado.',
            'error' => $error,
        ];
    }

    public static function respuesta404($error)
    {
        return $respuesta = [
            'codigo' => 404,
            'mensaje' => 'No se encontro solicitud.',
            'error' => $error,
        ];
    }
}
