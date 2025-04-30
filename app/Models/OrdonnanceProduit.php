<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdonnanceProduit
 * 
 * @property int $quantite
 * @property bool $statut
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $secretaire_id
 * @property int $pharmaceutical_product_id
 * @property int $ordonnance_id
 * 
 * @property Ordonnance $ordonnance
 * @property PharmaceuticalProduct $pharmaceutical_product
 * @property Secretaire $secretaire
 *
 * @package App\Models
 */
class OrdonnanceProduit extends Model
{
	use SoftDeletes;
	protected $table = 'ordonnance_produits';
	public $incrementing = false;

	protected $casts = [
		'quantite' => 'int',
		'statut' => 'bool',
		'secretaire_id' => 'int',
		'pharmaceutical_product_id' => 'int',
		'ordonnance_id' => 'int'
	];

	protected $hidden = [
		'secretaire_id'
	];

	protected $fillable = [
		'quantite',
		'statut',
		'secretaire_id'
	];

	public function ordonnance()
	{
		return $this->belongsTo(Ordonnance::class);
	}

	public function pharmaceutical_product()
	{
		return $this->belongsTo(PharmaceuticalProduct::class);
	}

	public function secretaire()
	{
		return $this->belongsTo(Secretaire::class);
	}
}
