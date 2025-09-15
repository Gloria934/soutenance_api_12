<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'permission_id' => 'int',
        'role_id' => 'int',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
