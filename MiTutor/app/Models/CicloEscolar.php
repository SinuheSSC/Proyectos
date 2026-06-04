<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicloEscolar extends Model
{
    protected $table = 'ciclo_escolar';
    protected $primaryKey = 'idCicloEscolar';
    public $timestamps = true;
    protected $fillable = [
        'ciclo',
        'year',
        'sesiones',
        'idCuentaTutor',
    ];

    public function tutor()  {
        return $this->belongsTo(Tutor::class,'idCuentaTutor','idCuentaTutor');
    }
}
