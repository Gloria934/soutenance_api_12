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
 * Class Pharmacien
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|Ordonnance[] $ordonnances
 *
 * @package App\Models
 */
class Pharmacien extends Model
{
	use SoftDeletes;
	protected $table = 'pharmaciens';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id');
	}

	public function ordonnances()
	{
		return $this->hasMany(Ordonnance::class);
	}
}
