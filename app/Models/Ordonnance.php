<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $fillable = [
        'montant_total',
        'montant_paye',
        'patient_id',

    ];

    public function medicaments_prescrits()
    {
        return $this->hasMany(MedicamentPrescrit::class);
    }

    public function pharmaceutical_products(){
        return $this->hasManyThrough(PharmaceuticalProduct::class,MedicamentPrescrit::class);
    }

    
    public function patient()
    {
        return $this->belongsTo(User::class);
    }
}
