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
 * Class SousCategory
 * 
 * @property int $id
 * @property string $nom
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $categorie_id
 * 
 * @property Category $category
 * @property Collection|ProduitSousCategory[] $produit_sous_categories
 *
 * @package App\Models
 */
class SousCategory extends Model
{
	use SoftDeletes;
	protected $table = 'sous_categories';

	protected $casts = [
		'categorie_id' => 'int'
	];

	protected $fillable = [
		'nom',
		'categorie_id'
	];

	public function category()
	{
		return $this->belongsTo(Category::class, 'categorie_id');
	}

	public function produit_sous_categories()
	{
		return $this->hasMany(ProduitSousCategory::class, 'sous_categorie_id');
	}
}
