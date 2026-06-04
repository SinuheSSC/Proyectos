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
        Schema::create('examenes_vocacionales', function (Blueprint $table) {
            $table->id('idExamen');
            $table->text('areasDeEspecialidad');
            $table->text('carrerasRecomendadas');
            $table->text('recomendaciones');
            $table->unsignedBigInteger('idCuentaTutorado');
            $table->timestamps();

            $table->foreign('idCuentaTutorado')->references('idCuentaTutorado')->on('tutorados')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examenes_vocacionales');
    }
};
