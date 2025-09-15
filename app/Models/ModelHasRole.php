<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ModelHasRole extends Model
{
    protected $table = 'model_has_roles';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'role_id' => 'int',
        'model_id' => 'int',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'model_id')
                    ->where('model_type', User::class);
    }
}

