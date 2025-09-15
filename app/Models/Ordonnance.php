<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    use HasFactory;
    protected $fillable = [
        'montant_total',
        'montant_paye',
        'code_ordonnance',
        'patient_id',
        'service_id',
        'specialiste_id',
        'statut',
    ];

    public function medicaments_prescrits()
    {
        return $this->hasMany(MedicamentPrescrit::class);
    }

    public function pharmaceutical_products()
    {
        return $this->hasManyThrough(PharmaceuticalProduct::class, MedicamentPrescrit::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->withTrashed();
    }
    public function specialiste()
    {
        return $this->belongsTo(User::class, 'specialiste_id')->withTrashed();
    }
}
