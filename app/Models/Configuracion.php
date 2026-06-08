<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    // 1. Especificamos el nombre exacto de la tabla en español
    protected $table = 'configuracions';

    // 2. Le decimos cuáles son las columnas de nuestro "Diccionario"
    protected $fillable = [
        'clave',
        'valor',
    ];

    /**
     * 3. Una función para leer la configuración súper rápido
     * desde cualquier parte de la aplicación.
     */
    public static function obtener($clave, $porDefecto = null)
    {
        return self::where('clave', $clave)->value('valor') ?? $porDefecto;
    }

    /**
     * 4. Devuelve todas las configuraciones agrupadas
     */
    public static function todas()
    {
        return (object) [
            'max_salidas'              => self::obtener('max_salidas', 3),
            'tiempo_espera'            => self::obtener('tiempo_espera_segundos', 300),
            'tiempo_cancelacion'       => self::obtener('tiempo_cancelacion', 5),
            'aseo_hombres_disponible'  => (bool) self::obtener('aseo_hombres_disponible', true),
            'aseo_mujeres_disponible'  => (bool) self::obtener('aseo_mujeres_disponible', true),
        ];
    }
}