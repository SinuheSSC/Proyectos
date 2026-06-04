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
        Schema::create('foda', function (Blueprint $table) {
            $table->id('idFoda');
            $table->string('fortaleza');
            $table->string('debilidad');
            $table->string('oportunidad');
            $table->string('amenazas');
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
        Schema::dropIfExists('foda');
    }
};
