<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registro extends Model
{

    protected $fillable = [
        'alumno_id', 
        'profesor_id', 
        'fecha_entrada', 
        'fecha_salida'
    ];

    protected $hidden = ["updated_at", "created_at"];


    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }
}