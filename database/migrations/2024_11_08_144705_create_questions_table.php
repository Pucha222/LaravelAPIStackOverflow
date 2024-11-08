<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id')->unique(); //id externo
            $table->string('title');
            $table->string('link');
            $table->text('tags');
            $table->timestamp('creation_date'); //creado en stackoverflow por creador original
            $table->timestamps(); //timestamps de laravel nativos
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
