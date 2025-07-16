<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RendezVous
 * 
 * @property int $id
 * @property Carbon $date_rdv
 * @property string $statut
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $patient_id
 * @property int $service_id
 * 
 * @property Service $service
//  * @property Secretaire $secretaire
 *
 * @package App\Models
 */
class RendezVous extends Model
{
    use SoftDeletes;
    protected $table = 'rendez_vous';

    // protected $casts = [
    //     'date_rdv' => 'datetime',
    //     'service_id' => 'int'
    // ];


    protected $fillable = [
        'nom_visiteur',
        'prenom_visiteur',
        'numero_visiteur',
        'date_rdv',
        'patient_id',
        'statut',
        'service_id',
        'code_rendez_vous',
        'specialiste_id',
    ];




    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function specialiste()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class);
    }

}
