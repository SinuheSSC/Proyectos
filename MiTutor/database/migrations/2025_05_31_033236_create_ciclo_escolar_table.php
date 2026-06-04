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
        Schema::create('ciclo_escolar', function (Blueprint $table) {
            $table->id('idCicloEscolar');
            $table->enum('ciclo',['Enero-Junio','Agosto-Diciembre']);
            $table->string('year');
            $table->integer('sesiones');
            $table->unsignedBigInteger('idCuentaTutor');
            $table->foreign('idCuentaTutor')->references('idCuentaTutor')->on('tutores')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclo_escolar');
    }
};
