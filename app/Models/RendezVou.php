<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RendezVou
 * 
 * @property int $id
 * @property Carbon $date_rdv
 * @property string $statut
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $secretaire_id
 * @property int $service_id
 * 
 * @property Service $service
 * @property Secretaire $secretaire
 *
 * @package App\Models
 */
class RendezVou extends Model
{
	use SoftDeletes;
	protected $table = 'rendez_vous';

	protected $casts = [
		'date_rdv' => 'datetime',
		'secretaire_id' => 'int',
		'service_id' => 'int'
	];

	protected $hidden = [
		'secretaire_id'
	];

	protected $fillable = [
		'date_rdv',
		'statut',
		'secretaire_id',
		'service_id'
	];

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	public function secretaire()
	{
		return $this->belongsTo(Secretaire::class);
	}
}
