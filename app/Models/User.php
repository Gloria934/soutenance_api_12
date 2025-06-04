<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Contracts\Auth\MustVerifyEmail;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


use Spatie\Permission\Traits\HasRoles;

use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPassword;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Allergy;


/**
 * Class User
 * 
 * @property int $id
 * @property string $nom
 * @property string $prenom
 * @property string $telephone
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $device_token
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 



 *
 * @package App\Models
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, HasApiTokens, Notifiable, HasRoles, HasFactory;
    protected $table = 'users';

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'code_patient',
        'genre',
        'date_naissance',
        'npi',
        'role_voulu',
        'service_voulu',
        'email',
        'email_verified_at',
        'password',
        'device_token',
        'remember_token'
    ];










    // Dans le modèle User.php

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }
    public function allergies()
    {
        return $this->hasMany(Allergy::class);
    }



    // ...

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    // Ajoutez cette méthode pour toujours considérer l'email comme vérifié
    public function hasVerifiedEmail()
    {
        return true;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'personnel_sante_id');
    }

}
