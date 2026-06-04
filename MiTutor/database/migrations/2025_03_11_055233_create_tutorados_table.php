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
        Schema::create('tutorados', function (Blueprint $table) {
            $table->id('idCuentaTutorado');
            $table->string('nombres');
            $table->string('apellidoPaterno');
            $table->string('apellidoMaterno');
            $table->date('fechaNacimiento');
            $table->string('curp',18); //CURP FORANEA
            $table->enum('genero',['M','F']);
            $table->unsignedBigInteger('idGrupo'); //LLAVE FORANEA DE GRUPOS
            $table->string('canalizacion')->nullable();
            $table->string('carrera');
            $table->text('razonesCarrera')->nullable();
            $table->string('telefono');
            $table->enum('estadoCivil',['Soltero/a','Casado/a','Viudo/a']);
            $table->string('email');
            $table->string('municipio');
            $table->string('estado');
            $table->string('calleYnumero');
            $table->string('codigoPostal');
            $table->string('discapacidadFisica')->nullable();
            $table->string('enfemerdad')->nullable();
            $table->string('situacionPsicologica')->nullable();
            $table->text('necesidadEspecial')->nullable();
            $table->text('fotoPerfil')->nullable();
            $table->timestamps();

            $table->foreign('curp')->references('curp')->on('users')->onDelete('cascade');
            $table->foreign('idGrupo')->references('idGrupo')->on('grupos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorados');
    }
};
