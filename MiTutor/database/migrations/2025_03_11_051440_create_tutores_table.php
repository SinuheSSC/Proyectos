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
        Schema::create('tutores', function (Blueprint $table) {
            $table->id('idCuentaTutor');
            $table->string('nombres');
            $table->string('apellidoPaterno');
            $table->string('apellidoMaterno');
            $table->date('fechaNacimiento');
            $table->string('curp',18); //CURP FORANEA
            $table->string('RFC',14)->unique();
            $table->enum('genero',['M','F']);
            $table->string('cedulaProfesional');
            $table->string('especialidad');
            $table->string('titulo');
            $table->string('telefono');
            $table->enum('estadoCivil',['Soltero/a','Casado/a','Viudo/a']);
            $table->string('email');
            $table->string('municipio');
            $table->string('estado');
            $table->string('calleYnumero');
            $table->string('codigoPostal');
            $table->text('fotoPerfil')->nullable();
            $table->timestamps();

            $table->foreign('curp')->references('curp')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutores');
    }
};
