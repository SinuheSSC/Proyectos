<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamenVocacional extends Model
{
    protected $table = 'examenes_vocacionales';
    protected $primaryKey = 'idExamen';
    public $timestamps = true;
    protected $fillable = [
        'areasDeEspecialidad',
        'carrerasRecomendadas',
        'recomendaciones',
        'idCuentaTutorado',
    ];

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
