<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $table = 'alumnos';
    // Definimos qué campos se pueden rellenar automáticamente al crear un alumno
    protected $fillable = ['nre','nombre', 'apellidos', 'curso'];

    protected $hidden = ["updated_at", "created_at"];

    // RELACIÓN: Un alumno pertenece a una sola aula (Muchos a 1)
    public function aula(): BelongsTo
    {
        // El alumno tiene una columna 'aula_id' que lo conecta con su aula
        return $this->belongsTo(Aula::class);
    }

    // RELACIÓN: Un alumno puede tener muchos registros o fichajes (1 a Muchos)
    public function registros(): HasMany
    {
        // Buscamos todos los registros que tengan el ID de este alumno
        return $this->hasMany(Registro::class);
    }
}