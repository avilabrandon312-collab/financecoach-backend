<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    // Esto le dice a Laravel qué columnas tiene permitido escribir en la BD
    protected $fillable = [
        'user_id', 
        'category_id', // ¡Crucial!
        'amount', 
        'description', 
        'date',
        'context',
        'business_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}