<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'marca',
        'modelo',
        'cor',
        'ano',
        'placa',
        'preco',
        'imagem',
    ];

    // Relacionamento com Categoria
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}