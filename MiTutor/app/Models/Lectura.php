<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    //

    protected $table = 'lectura';
    protected $primaryKey = 'idLectura';
    public $timestamps = true;
    protected $fillable = [
        'nivelComprensionLectora',
        'idCuentaTutorado',
    ];

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
