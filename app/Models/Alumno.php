<?php

namespace App\Models;

use App\Enums\Genero;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $table = 'alumnos';


    protected $fillable = ['nre','nombre', 'apellidos', 'curso_id', 'excepcion_limite', 'necesita_tutor'];

    protected $hidden = ["updated_at", "created_at"];

    protected $casts = ['genero' => Genero::class];

    public function registros(): HasMany
    {
        return $this->hasMany(Registro::class);
    }
    
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }
}