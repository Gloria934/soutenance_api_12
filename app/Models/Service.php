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
 * Class Service
 * 
 * @property int $id
 * @property string $nom
 * @property string $telephone
 * @property string $email
 * @property string $profile_illustratif
 * @property float $prix_rdv
 * @property Carbon $heure_ouverture
 * @property Carbon $heure_fermeture
 * @property Carbon $duree_moy_rdv
 * @property bool $sous_rdv
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $admin_id
 * 
 * @property Admin $admin
 * @property Collection|RendezVou[] $rendez_vous
 *
 * @package App\Models
 */
class Service extends Model
{
	use SoftDeletes;
	protected $table = 'services';

	protected $casts = [
		'prix_rdv' => 'float',
		'heure_ouverture' => 'datetime',
		'heure_fermeture' => 'datetime',
		'duree_moy_rdv' => 'datetime',
		'sous_rdv' => 'bool',
		'admin_id' => 'int'
	];

	protected $fillable = [
		'nom',
		'telephone',
		'email',
		'profile_illustratif',
		'prix_rdv',
		'heure_ouverture',
		'heure_fermeture',
		'duree_moy_rdv',
		'sous_rdv',
		'admin_id'
	];

	public function admin()
	{
		return $this->belongsTo(Admin::class);
	}

	public function rendez_vous()
	{
		return $this->hasMany(RendezVou::class);
	}
}
