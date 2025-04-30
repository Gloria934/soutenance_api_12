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
 * Class Stock
 * 
 * @property int $id
 * @property int $quantite_disponible
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $secretaire_id
 * 
 * @property Secretaire $secretaire
 * @property Collection|PharmaceuticalProduct[] $pharmaceutical_products
 *
 * @package App\Models
 */
class Stock extends Model
{
	use SoftDeletes;
	protected $table = 'stocks';

	protected $casts = [
		'quantite_disponible' => 'int',
		'secretaire_id' => 'int'
	];

	protected $hidden = [
		'secretaire_id'
	];

	protected $fillable = [
		'quantite_disponible',
		'secretaire_id'
	];

	public function secretaire()
	{
		return $this->belongsTo(Secretaire::class);
	}

	public function pharmaceutical_products()
	{
		return $this->hasMany(PharmaceuticalProduct::class);
	}
}
