<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analyse extends Model
{
    protected $table = 'analyses';

    protected $fillable = [
        'nom',
        'description',
        'prix',
    ];
    public function analyse_patients()
    {
        return $this->hasMany(AnalysePatient::class);
    }
    
}
