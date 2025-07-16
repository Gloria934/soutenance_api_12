<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangueSpecialite extends Model
{
    protected $table = 'langues_specialites';

    protected $fillable = [
        'langue_id',
        'specialite_id',
    ];

    public function langues()
    {
        return $this->belongsTo(Langue::class);
    }

    public function specialites()
    {
        return $this->belongsTo(Specialite::class);
    }
}
