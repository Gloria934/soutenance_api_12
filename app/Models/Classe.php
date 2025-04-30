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
 * Class Class
 * 
 * @property int $id
 * @property string $nom
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ProduitClass[] $produit_classes
 *
 * @package App\Models
 */
class Classe extends Model
{
	use SoftDeletes;
	protected $table = 'classes';

	protected $fillable = [
		'nom'
	];

	public function produit_classes()
	{
		return $this->hasMany(ProduitClass::class, 'classe_id');
	}
}
