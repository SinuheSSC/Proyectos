<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoradosActividad extends Model
{
    protected $table = 'tutorados_actividad';
    protected $primaryKey = ['idCuenta','idActividad'];
    public $timestamps = true;
    protected $fillable = [
        'idCuentaTutorado',
        'idActividad',
        'tiempoLimite',
        'terminada',
        'resultado'
    ];

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
