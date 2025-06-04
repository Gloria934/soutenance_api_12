<?php

// app/Models/Notification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleNotification extends Model
{
    protected $fillable = ['personnel_sante_id', 'type', 'status'];

    public function personnelSante()
    {
        return $this->belongsTo(User::class, 'personnel_sante_id');
    }
}
