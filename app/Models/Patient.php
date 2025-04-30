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
 * Class Patient
 * 
 * @property int $id
 * @property string $patient_code
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Ordonnance[] $ordonnances
 * @property Collection|Paiement[] $paiements
 *
 * @package App\Models
 */
class Patient extends Model
{
	use SoftDeletes;
	protected $table = 'patients';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'patient_code'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id');
	}

	public function ordonnances()
	{
		return $this->hasMany(Ordonnance::class);
	}

	public function paiements()
	{
		return $this->hasMany(Paiement::class);
	}



	// App\Models\Patient.php
	public static function generatePatientCode()
	{
		$lastPatient = self::latest('id')->first();

		$number = $lastPatient ? $lastPatient->id + 1 : 1;

		return 'PAT-' . str_pad($number, 4, '0', STR_PAD_LEFT);
	}

}
