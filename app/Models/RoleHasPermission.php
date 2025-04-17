<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RoleHasPermission
 * 
 * @property int $permission_id
 * @property int $role_id
 * 
 * @property Role $role
 * @property Permission $permission
 *
 * @package App\Models
 */
class RoleHasPermission extends Model
{
	protected $table = 'role_has_permissions';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'permission_id' => 'int',
		'role_id' => 'int'
	];

	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function permission()
	{
		return $this->belongsTo(Permission::class);
	}
}
