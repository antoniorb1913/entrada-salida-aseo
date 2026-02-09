<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registro extends Model
{
    // Campos de la base de datos que permitimos guardar masivamente
    protected $fillable = [
        'alumno_id', 
        'profesor_id', 
        'fecha_entrada', 
        'fecha_salida'
    ];

    protected $hidden = ["updated_at", "created_at"];

    // RELACIÓN: Indica que este registro pertenece a un único alumno
    public function alumno(): BelongsTo
    {
        // El registro busca al alumno usando la columna 'alumno_id'
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    // RELACIÓN: Cada registro pertenece al profesor (User) que lo autorizó
    public function profesor(): BelongsTo
    {
        // Relacionamos el registro con el modelo User usando 'profesor_id'
        return $this->belongsTo(User::class, 'profesor_id');
    }
}