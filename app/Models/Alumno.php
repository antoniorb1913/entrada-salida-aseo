<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $table = 'alumnos';
    // Definimos qué campos se pueden rellenar automáticamente al crear un alumno
    protected $fillable = ['nre','nombre', 'apellidos', 'curso_id'];

    protected $hidden = ["updated_at", "created_at"];
    
    // RELACIÓN: Un alumno puede tener muchos registros o fichajes (1 a Muchos)
    public function registros(): HasMany
    {
        // Buscamos todos los registros que tengan el ID de este alumno
        return $this->hasMany(Registro::class);
    }

    // RELACIÓN: Una aula puede tener muchos alumnos (1 a Muchos)
    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}