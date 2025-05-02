<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Patient;
use App\Models\Pharmacien;
use App\Models\Secretaire;
class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        match($user->getRoleNames()->first()) {
            'admin' => Admin::create(['id' => $user->id]),
            'patient' => Patient::create(['id' => $user->id]),
            'pharmacien' => Pharmacien::create(['id' => $user->id]),
            'secrétaire' => Secretaire::create(['id' => $user->id]),
            default => null
        };
    }


    

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleting(User $user)
    {
        $role = $user->getRoleNames()->first();
        
        match($role) {
            'admin' => $user->admin?->delete(),
            'patient' => $user->patient?->delete(),
            'pharmacien' => $user->pharmacien?->delete(),
            'secrétaire' => $user->secretaire?->delete(),
            default => null
        };
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}


    
    
