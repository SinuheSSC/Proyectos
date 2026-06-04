<?php

namespace App\Models;

use App\Livewire\Examenv;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $primaryKey = 'idActividad';
    public $timestamps = true;
    protected $fillable = [
        'idLectura',
        'idVida',
        'idFoda',
        'idExamen',
    ];

    public function vida()  {
        return $this->belongsTo(Vida::class,'idVida','idVida');
    }

    public function lectura()  {
        return $this->belongsTo(Lectura::class,'idLectura','idLectura');
    }

    public function foda()  {
        return $this->belongsTo(Foda::class,'idFoda','idFoda');
    }

    public function examen()  {
        return $this->belongsTo(ExamenVocacional::class,'idExamen','idExamen');
    }

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
