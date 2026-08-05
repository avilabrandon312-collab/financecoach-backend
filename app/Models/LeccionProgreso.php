<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeccionProgreso extends Model
{
    use HasFactory;

    protected $table = 'leccion_progresos';

    protected $fillable = [
        'user_id',
        'leccion_id',
        'completada',
        'completada_en',
    ];

    protected $casts = [
        'completada' => 'boolean',
        'completada_en' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leccion()
    {
        return $this->belongsTo(Leccion::class);
    }
}
