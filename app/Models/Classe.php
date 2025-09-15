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
 * @package App\Models
 */
class Classe extends Model
{
	use SoftDeletes;
	protected $table = 'classes';

	protected $fillable = [
		'nom'
	];

	public function pharmaceutical_products()
	{
		return $this->hasMany(PharmaceuticalProduct::class, 'classe_id');
	}

}
