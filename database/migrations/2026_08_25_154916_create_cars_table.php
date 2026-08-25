<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id(); 
            $table->string('marca'); 
            $table->string('modelo');
            $table->integer('ano');
            $table->string('placa')->unique(); 
            $table->string('cor');
            $table->decimal('preco', 12, 2); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};