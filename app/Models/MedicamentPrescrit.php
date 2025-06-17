<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdonnanceProduit
 * 
 * @property int $quantite
 * @property bool $statut
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $secretaire_id
 * @property int $pharmaceutical_product_id
 * @property int $ordonnance_id
 * 
 * @property Ordonnance $ordonnance
 * @property PharmaceuticalProduct $pharmaceutical_product
//  * @property Secretaire $secretaire
 *
 * @package App\Models
 */
class MedicamentPrescrit extends Model
{
    use SoftDeletes;
    protected $table = 'medicaments_prescrits';



    protected $fillable = [
        'ordonnance_id',
        'quantite',
        'statut',
        'posologie',
        'duree',
        'avis',
        'substitution_autorisee',
    ];

    public function ordonnance()
    {
        return $this->belongsTo(Ordonnance::class);
    }

    public function pharmaceutical_product()
    {
        return $this->belongsTo(PharmaceuticalProduct::class);
    }

    // public function secretaire()
    // {
    //     return $this->belongsTo(Secretaire::class);
    // }
}
