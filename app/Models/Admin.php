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
 * Class Admin
 * 
 * @property int $id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Service[] $services
 *
 * @package App\Models
 */
class Admin extends Model
{
	use SoftDeletes;
	protected $table = 'admins';
	public $incrementing = false;

	protected $casts = [
		'id' => 'int'
	];
	
	protected $fillable = ['user_id'];

	public function user()
	{
		return $this->belongsTo(User::class, 'id');
	}

	public function services()
	{
		return $this->hasMany(Service::class);
	}
}
