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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id('idActividad');
            $table->unsignedBigInteger('idLectura');
            $table->unsignedBigInteger('idVida');
            $table->unsignedBigInteger('idFoda');
            $table->unsignedBigInteger('idExamen');
            $table->unsignedBigInteger('idCuentaTutorado');
            $table->timestamps();

            $table->foreign('idLectura')->references('idLectura')->on('lectura')->onDelete('cascade');
            $table->foreign('idVida')->references('idVida')->on('vida')->onDelete('cascade');
            $table->foreign('idFoda')->references('idFoda')->on('foda')->onDelete('cascade');
            $table->foreign('idExamen')->references('idExamen')->on('examenes_vocacionales')->onDelete('cascade');
            $table->foreign('idCuentaTutorado')->references('idCuentaTutorado')->on('tutorados')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad');
    }
};
