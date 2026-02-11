<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    protected $table = 'aulas';
    // Campos que permitimos rellenar (en este caso solo el nombre del aula)
    protected $fillable = ['nombre'];

    protected $hidden = ["updated_at", "created_at"];

    // RELACIÓN: Una aula puede tener muchos alumnos (1 a Muchos)
    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class);
    }
}