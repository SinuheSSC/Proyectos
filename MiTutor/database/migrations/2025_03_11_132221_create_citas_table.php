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
        Schema::create('citas', function (Blueprint $table) {
            $table->id('idCita');
            $table->dateTime('fechaCita');
            $table->unsignedBigInteger('idCuentaTutorado');
            $table->string('descripcion');
            $table->enum('canalizacion',['Psicologia','Enfermeria']);
            $table->text('resultados')->nullable();
            $table->timestamps();

            $table->foreign('idCuentaTutorado')->references('idCuentaTutorado')->on('tutorados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
