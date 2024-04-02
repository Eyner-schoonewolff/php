<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // se agrega el nombre del nombre de las tabla
    protected $table = 'usuario';

    // para desactivar los campos de tiempo que se crean automaticamente.
    public $timestamps = false;

    use HasFactory;
}
