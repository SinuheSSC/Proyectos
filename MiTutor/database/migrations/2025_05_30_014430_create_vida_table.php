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
        Schema::create('vida', function (Blueprint $table) {
            $table->id('idVida');
            $table->string('hitoUno');
            $table->date('fechaHitoUno');

            $table->string('hitoDos');
            $table->date('fechaHitoDos');

            $table->string('hitoTres');
            $table->date('fechaHitoTres');

            $table->string('hitoCuatro');
            $table->date('fechaHitoCuatro');

            $table->string('hitoCinco');
            $table->date('fechaHitoCinco');

            $table->timestamps();

            $table->unsignedBigInteger('idCuentaTutorado');
            $table->foreign('idCuentaTutorado')->references('idCuentaTutorado')->on('tutorados')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vida');
    }
};
