<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProduitSousCategory
 * 
 * @property int $sous_categorie_id
 * @property int $pharmaceutical_product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property PharmaceuticalProduct $pharmaceutical_product
 * @property SousCategory $sous_category
 *
 * @package App\Models
 */
class ProduitSousCategory extends Model
{
	protected $table = 'produit_sous_categories';
	public $incrementing = false;

	protected $casts = [
		'sous_categorie_id' => 'int',
		'pharmaceutical_product_id' => 'int'
	];

	public function pharmaceutical_product()
	{
		return $this->belongsTo(PharmaceuticalProduct::class);
	}

	public function sous_category()
	{
		return $this->belongsTo(SousCategory::class, 'sous_categorie_id');
	}
}
