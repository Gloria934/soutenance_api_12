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
 * Class Ordonnance
 * 
 * @property int $id
 * @property float $montant
 * @property Carbon $date_ordonnance
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $pharmacien_id
 * @property int $patient_id
 * 
 * @property Patient $patient
 * @property Pharmacien $pharmacien
 * @property Collection|OrdonnanceProduit[] $ordonnance_produits
 *
 * @package App\Models
 */
class Ordonnance extends Model
{
	use SoftDeletes;
	protected $table = 'ordonnances';

	protected $casts = [
		'montant' => 'float',
		'date_ordonnance' => 'datetime',
		'pharmacien_id' => 'int',
		'patient_id' => 'int'
	];

	protected $fillable = [
		'montant',
		'date_ordonnance',
		'pharmacien_id',
		'patient_id'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class);
	}

	public function pharmacien()
	{
		return $this->belongsTo(Pharmacien::class);
	}

	public function ordonnance_produits()
	{
		return $this->hasMany(OrdonnanceProduit::class);
	}
}
