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
		'categorie_id',
		'nom',

	];

	public function category()
	{
		return $this->belongsTo(Category::class, 'categorie_id');
	}

	public function pharmaceutical_products()
	{
		return $this->hasMany(PharmaceuticalProduct::class, 'sous_categorie_id');
	}

}
