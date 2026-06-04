<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'idCita';
    public $timestamps = true;
    protected $fillable = [
        'fechaCita',
        'idCuentaTutorado',
        'tipo',
        'canalizacion',
        'resultados',
        'descripcion'
    ];

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
