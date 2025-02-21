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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');

            $table->unsignedBigInteger('areas_id');
            $table->foreign('areas_id')->references('id')->on('areas');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->date('fecha_inicio');
            $table->date('hora_inicio');
            $table->date('fecha_fin');
            $table->date('hora_fin');
            
            $table->string('notas_cta')->nullable();
            $table->string('notas_generales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
