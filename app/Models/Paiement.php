<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Paiement
 * 
 * @property int $id
 * @property float $prix_total
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $patient_id
 * 
 * @property Patient $patient
 *
 * @package App\Models
 */
class Paiement extends Model
{
	use SoftDeletes;
	protected $table = 'paiements';

	protected $casts = [
		'prix_total' => 'float',
		'patient_id' => 'int'
	];

	protected $fillable = [
		'prix_total',
		'patient_id'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class);
	}
}
