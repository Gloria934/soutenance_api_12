<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'nom_medecin',
        'prix_rdv',
        'heure_ouverture',
        'heure_fermeture',
        'duree_moy_rdv',
        'sous_rdv',

    ];


    public function rendezvous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function specialiste()
    {
        return $this->hasMany(User::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }


}