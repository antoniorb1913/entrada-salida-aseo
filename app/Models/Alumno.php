<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $fillable = ['NRE','nombre', 'apellidos', 'curso', 'profesor_id', 'aula_id'];

    // El alumno pertenece a un aula
    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class);
    }

    public function registros(): HasMany
    {
        // Al usar 'id', Laravel ya sabe cómo conectarlos, solo necesitas el nombre de la clase
        return $this->hasMany(Registro::class);
    }
}
