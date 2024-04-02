<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class Usuario extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];
}
