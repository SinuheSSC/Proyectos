<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = ['idCuentaTutorado','asistencia'];
    public $timestamps = true;
    protected $fillable = [
        'idCuentaTutorado',
        'asistencia'
    ];

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
