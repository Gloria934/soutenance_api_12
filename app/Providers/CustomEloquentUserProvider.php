<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class CustomEloquentUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (!in_array($key, ['password', 'password_confirmation'])) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }
}