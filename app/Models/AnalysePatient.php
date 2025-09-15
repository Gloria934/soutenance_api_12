<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalysePatient extends Model
{
    protected $table = 'analyse_patients';

    protected $fillable = [
        'analyse_id',
        'patient_id',
        'date_rdv',
        'code_analyse_patient',
        'statut',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class);
    }
    public function analyse()
    {
        return $this->belongsTo(Analyse::class);
    }
}
