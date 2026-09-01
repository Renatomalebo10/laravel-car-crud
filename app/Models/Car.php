<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    /**
     * Atributos que podem ser preenchidos em massa (Mass Assignment).
     */
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

    /**
     * Relação N:1 (Muitos carros pertencem a uma Categoria).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}