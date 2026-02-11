<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importante añadir esto

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Indica que este modelo usa tu tabla 'profesor'
    protected $table = 'profesors';

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',     
        'apellidos',  
        'email',
        'password',       
        'rol'    
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'updated_at', 
        'created_at'
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Un profesor puede tener muchos alumnos asignados.
     */
    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'profesor_id');
    }

    /**
     * Un profesor autoriza muchos registros de salida al baño.
     */
    public function registros(): HasMany
    {
        return $this->hasMany(Registro::class, 'profesor_id');
    }
    /**
     * Indica a Laravel que use el campo 'nombre' para la autenticación.
     */
    public function username()
    {
        return 'nombre';
    }
}