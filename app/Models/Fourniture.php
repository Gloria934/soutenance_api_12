<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fourniture extends Model
{
    protected $table = "fournitures";

    protected $fillable = [
        'nom',
        'prix',
        'quantite',

        'image',

    ];
}
