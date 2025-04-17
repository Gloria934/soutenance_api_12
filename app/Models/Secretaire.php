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
 * Class Secretaire
 * 
 * @property int $id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|OrdonnanceProduit[] $ordonnance_produits
 * @property Collection|PharmaceuticalProduct[] $pharmaceutical_products
 * @property Collection|RendezVou[] $rendez_vous
 * @property Collection|Stock[] $stocks
 *
 * @package App\Models
 */
class Secretaire extends Model
{
	use SoftDeletes;
	protected $table = 'secretaires';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id');
	}

	public function ordonnance_produits()
	{
		return $this->hasMany(OrdonnanceProduit::class);
	}

	public function pharmaceutical_products()
	{
		return $this->hasMany(PharmaceuticalProduct::class);
	}

	public function rendez_vous()
	{
		return $this->hasMany(RendezVou::class);
	}

	public function stocks()
	{
		return $this->hasMany(Stock::class);
	}
}
