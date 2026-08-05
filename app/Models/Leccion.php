<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leccion extends Model
{
    use HasFactory;

    // Nombre explícito de la tabla en la base de datos
    protected $table = 'lecciones';

    // Asignación masiva permitida para los campos del formulario
    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'video_url',
        'parametro_formacional',
    ];

    // Casteo automático para metadatos o parámetros formacionales
    protected $casts = [
        'parametro_formacional' => 'array',
    ];
}