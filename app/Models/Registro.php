<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\Estado;

class Registro extends Model
{

    protected $fillable = [
        'alumno_id', 
        'profesor_id',
        'curso_id',
        'fecha_salida',
        'fecha_entrada',
        'estado',
    ];

    protected $casts = [
        'estado' => Estado::class,
        'fecha_salida' => 'datetime',
        'fecha_entrada' => 'datetime',
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