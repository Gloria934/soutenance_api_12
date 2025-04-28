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
 * @property string $profile_illustratif
 * @property string|null $remember_token
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Admin|null $admin
 * @property Patient|null $patient
 * @property Pharmacien|null $pharmacien
 * @property Secretaire|null $secretaire
 *
 * @package App\Models
 */
class User extends Authenticatable implements MustVerifyEmail
{
	use SoftDeletes,HasApiTokens, Notifiable, HasRoles, HasFactory;
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
		'email',
		'email_verified_at',
		'password',
		'profile_illustratif',
		'remember_token'
	];

	public function admin()
	{
		return $this->hasOne(Admin::class, 'id');
	}

	public function patient()
	{
		return $this->hasOne(Patient::class, 'id');
	}

	public function pharmacien()
	{
		return $this->hasOne(Pharmacien::class, 'id');
	}

	public function secretaire()
	{
		return $this->hasOne(Secretaire::class, 'id');
	}


	// Dans le modèle User.php

	public function sendEmailVerificationNotification()
	{
		$this->notify(new CustomVerifyEmail());
	}



// ...

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new CustomResetPassword($token));
	}


}
