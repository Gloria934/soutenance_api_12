<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PharmaceuticalProduct
 * 
 * @property int $id
 * @property string $nom_produit
 * @property string $image_path
 * @property float $dosage
 * @property float $prix
 * @property string $description
 * @property Carbon $date_expiration
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $stock
 * @property int $dci_id
 * @property int $classe_id
 * @property int $categorie_id
 * @property int $sous_categorie_id
 * @property int $forme_id
 * 
 * @property Forme $forme
 * @property Dci $dci

 *
 * @package App\Models
 */
class PharmaceuticalProduct extends Model
{
    use SoftDeletes;
    protected $table = 'pharmaceutical_products';

    protected $casts = [
        'dosage' => 'string',
        'prix' => 'float',
        'date_expiration' => 'datetime',
        'stock' => 'int',

    ];


    protected $fillable = [
        'nom_produit',
        'dosage',
        'classe_id',
        'image_path',
        'prix',
        'description',
        'date_expiration',
        'stock',
        'categorie_id',
        'sous_categorie_id',
        'dci_id',
        'forme_id'
    ];



    public function dci()
    {
        return $this->belongsTo(Dci::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Category::class);
    }
    public function sousCategorie()
    {
        return $this->belongsTo(SousCategory::class);
    }
    public function forme()
    {
        return $this->belongsTo(Forme::class);
    }
    // public function ordonnance_produits()
    // {
    //     return $this->hasMany(OrdonnanceProduit::class);
    // }






}
