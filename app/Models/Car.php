<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}