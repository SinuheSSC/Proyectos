<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tutorado extends Model
{
    protected $table = 'tutorados';
    protected $primaryKey = 'idCuentaTutorado';
    public $timestamps = true;
    protected $fillable = [
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'fechaNacimiento',
        'curp',
        'genero',
        'idGrupo',
        'canalizacion',
        'carrera',
        'razonesCarrera',
        'telefono',
        'estadoCivil',
        'email',
        'municipio',
        'estado',
        'calleYnumero',
        'codigoPostal',
        'discapacidadFisica',
        'enfemerdad',
        'situacionPsicologica',
        'necesidadEspecial',
        'fotoPerfil'
    ];

    public function user() {
        return $this->belongsTo(User::class,'curp','curp');
    }

    public function grupo(){
        return $this->belongsTo(Grupo::class,'idGrupo','idGrupo');

    }
}
