<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

use App\Models\User;

class ModelHasPermission extends Model
{
    protected $table = 'model_has_permissions';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'permission_id' => 'int',
        'model_id' => 'int',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function user()
    {
        // Toujours filtrer sur model_type = User::class
        return $this->belongsTo(User::class, 'model_id')
                    ->where('model_type', User::class);
    }
}

