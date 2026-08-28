<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Importação do Model
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model // <-- Adicionar "extends Model"
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao'];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}