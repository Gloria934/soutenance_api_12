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
 * Class Forme
 * 
 * @property int $id
 * @property string $nom
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 

 *
 * @package App\Models
 */
class Forme extends Model
{
	use SoftDeletes;
	protected $table = 'formes';

	protected $fillable = [
		'nom'
	];

}
