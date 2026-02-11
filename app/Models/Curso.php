<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = "cursos";
    // Cambiamos 'etapa' por 'etapas' para que coincida con tu base de datos
    protected $fillable = ['etapas', 'nivel', 'letra'];

    protected $hidden = ["updated_at", "created_at"];

    public function alumnos() {
        return $this->hasMany(Alumno::class);
    }
}