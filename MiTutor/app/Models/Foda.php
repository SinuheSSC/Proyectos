<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foda extends Model
{
    //

    protected $table = 'foda';
    protected $primaryKey = 'idFoda';
    public $timestamps = true;
    protected $fillable = [
        'fortaleza',
        'debilidad',
        'oportunidad',
        'amenazas',
        'idCuentaTutorado'
    ];

    public function tutorado()  {
        return $this->belongsTo(Tutorado::class,'idCuentaTutorado','idCuentaTutorado');
    }
}
