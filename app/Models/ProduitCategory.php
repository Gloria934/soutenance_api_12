<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProduitCategory
 * 
 * @property int $categorie_id
 * @property int $pharmaceutical_product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property PharmaceuticalProduct $pharmaceutical_product
 * @property Category $category
 *
 * @package App\Models
 */
class ProduitCategory extends Model
{
	protected $table = 'produit_categories';
	public $incrementing = false;

	protected $casts = [
		'categorie_id' => 'int',
		'pharmaceutical_product_id' => 'int'
	];

	public function pharmaceutical_product()
	{
		return $this->belongsTo(PharmaceuticalProduct::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'categorie_id');
	}
}
