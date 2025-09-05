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
 * @property int $montant
 * @property int $quantite
 * @property bool $livre
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at

 * 
 * @property User $patient
 * @property FournituresPatient $fournitures_patient

 *
 * @package App\Models
 */
class FournituresPatient extends Model
{
    use SoftDeletes;
    protected $table = 'fournitures_patients';



    protected $fillable = [
        'montant',
        'quantite',
        'code_fourniture',
        'livre',
        'patient_id',
        'fourniture_id',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class);
    }

    public function fournitures()
    {
        return $this->belongsTo(Fourniture::class, 'fourniture_id');
    }

}
