<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProduitClass
 * 
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $classe_id
 * @property int $pharmaceutical_product_id
 * 
 * @property PharmaceuticalProduct $pharmaceutical_product
 * @property Classe $class
 *
 * @package App\Models
 */
class ProduitClass extends Model
{
	protected $table = 'produit_classes';
	public $incrementing = false;

	protected $casts = [
		'classe_id' => 'int',
		'pharmaceutical_product_id' => 'int'
	];

	public function pharmaceutical_product()
	{
		return $this->belongsTo(PharmaceuticalProduct::class);
	}

	public function class()
	{
		return $this->belongsTo(Classe::class, 'classe_id');
	}
}
