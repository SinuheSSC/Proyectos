<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $table = 'tutores';
    protected $primaryKey = 'idCuentaTutor';
    public $timestamps = true;
    protected $fillable = [
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'fechaNacimiento',
        'curp',
        'RFC',
        'genero',
        'cedulaProfesional',
        'especialidad',
        'titulo',
        'telefono',
        'estadoCivil',
        'email',
        'municipio',
        'estado',
        'calleYnumero',
        'codigoPostal',
        'fotoPerfil'
    ];

    public function user() {
        return $this->belongsTo(User::class,'curp','curp');
    }

    public function grupo() {
        return $this->hasMany(Grupo::class,'idCuentaTutor','idCuentaTutor');
    }
}
