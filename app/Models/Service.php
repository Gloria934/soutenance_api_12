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


}