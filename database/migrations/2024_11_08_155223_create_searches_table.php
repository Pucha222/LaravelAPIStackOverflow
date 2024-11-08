<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->id(); // ID autoincrementable
            $table->string('busqueda_tagged'); // Campo tipo texto para la búsqueda
            $table->string('busqueda_fromdate'); // Campo tipo texto para la búsqueda
            $table->string('busqueda_todate'); // Campo tipo texto para la búsqueda
            $table->integer('contador')->default(0); // Campo tipo numérico para el contador, con valor por defecto 0
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('searches');
    }
};
