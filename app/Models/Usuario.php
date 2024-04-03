<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Model implements Authenticatable, JWTSubject
{
    use Notifiable;

    protected $table = 'usuario';
    public $timestamps = false;
    protected $fillable = [
        'nombre', 'correo', 'contrasenia', 'direccion', 'telefono', 'fecha_nacimiento',
    ];


    // Añadimos el campo 'contrasenia' como campo protegido
    protected $guarded = ['contrasenia'];

    // Definimos el evento para encriptar la contraseña antes de guardarla
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($usuario) {
            // Verificamos si la contraseña ha sido modificada
            if ($usuario->isDirty('contrasenia')) {
                // Encriptamos la contraseña con SHA-256
                $usuario->contrasenia = hash('sha256', $usuario->contrasenia);
            }
        });
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->contrasenia;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getRememberToken()
    {
        return null; // No se utiliza remember token
    }

    public function setRememberToken($value)
    {
        // No se utiliza remember token
    }

    public function getRememberTokenName()
    {
        return null; // No se utiliza remember token
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
