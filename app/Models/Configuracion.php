<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{

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
// En app/Models/Configuracion.php

    public static function todas()
    {
        return (object) [
            'max_salidas' => self::obtener('max_salidas', 3),
            'tiempo_espera' => self::obtener('tiempo_espera_segundos', 300),
        ];
    }
}