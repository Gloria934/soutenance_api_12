<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $fillable = [
        'montant_total',
        'montant_paye',

    ];

    public function medicaments_prescrits()
    {
        return $this->hasMany(MedicamentPrescrit::class);
    }
    public function patient()
    {
        return $this->belongsTo(User::class);
    }
}
