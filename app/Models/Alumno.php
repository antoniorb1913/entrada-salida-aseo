<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $table = 'alumnos';


    protected $fillable = ['nre','nombre', 'apellidos', 'curso_id'];

    protected $hidden = ["updated_at", "created_at"];
    

    public function registros(): HasMany
    {
        return $this->hasMany(Registro::class);
    }
    
    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}