<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    protected $table = 'aula';
    // Campos que permitimos rellenar (en este caso solo el nombre del aula)
    protected $fillable = ['nombre'];

    // RELACIÓN: Una aula puede tener muchos alumnos (1 a Muchos)
    public function alumnos(): HasMany
    {
        // Conectamos el aula con el modelo Alumno
        //hasMany --> Le dice a Laravel que un solo registro de una tabla está conectado 
                   // con varios registros de otra tabla.
        return $this->hasMany(Alumno::class);
    }
}