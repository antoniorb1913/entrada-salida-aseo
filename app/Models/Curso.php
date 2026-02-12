<?php

namespace App\Models;

use App\Enums\Etapas;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = "cursos";
    // Cambiamos 'etapa' por 'etapas' para que coincida con tu base de datos
    protected $fillable = ['nivel', 'letra'];

    protected $hidden = ["updated_at", "created_at"];

    protected $casts = ['etapas' => Etapas::class];
    
    public function alumnos() {
        return $this->hasMany(Alumno::class);
    }
}