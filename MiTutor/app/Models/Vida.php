<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vida extends Model
{
    //

    protected $table = 'vida';
    protected $primaryKey = 'idVida';
    public $timestamps = true;
    protected $fillable = [
        'hitoUno',
        'fechaHitoUno',
        'hitoDos',
        'fechaHitoDos',
        'hitoTres',
        'fechaHitoTres',
        'hitoCuatro',
        'fechaHitoCuatro',
        'hitoCinco',
        'fechaHitoCinco',
        'idCuentaTutorado'
    ];

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
