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
 * Class PharmaceuticalProduct
 * 
 * @property int $id
 * @property string $nom_produit
 * @property float $dosage
 * @property string $unite_mesure
 * @property string $code
 * @property string|null $nom_laboratoire
 * @property string $image_produit
 * @property float $prix
 * @property string $description
 * @property Carbon $date_expiration
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $stock_id
 * @property int $secretaire_id
 * @property int $dci_id
 * @property int $forme_id
 * 
 * @property Forme $forme
 * @property Dci $dci
 * @property Secretaire $secretaire
 * @property Stock $stock
 * @property Collection|OrdonnanceProduit[] $ordonnance_produits
 * @property Collection|ProduitCategory[] $produit_categories
 * @property Collection|ProduitClass[] $produit_classes
 * @property Collection|ProduitSousCategory[] $produit_sous_categories
 *
 * @package App\Models
 */
class PharmaceuticalProduct extends Model
{
	use SoftDeletes;
	protected $table = 'pharmaceutical_products';

	protected $casts = [
		'dosage' => 'float',
		'prix' => 'float',
		'date_expiration' => 'datetime',
		'stock_id' => 'int',
		'secretaire_id' => 'int',
		'dci_id' => 'int',
		'forme_id' => 'int'
	];

	protected $hidden = [
		'secretaire_id'
	];

	protected $fillable = [
		'nom_produit',
		'dosage',
		'unite_mesure',
		'code',
		'nom_laboratoire',
		'image_produit',
		'prix',
		'description',
		'date_expiration',
		'stock_id',
		'secretaire_id',
		'dci_id',
		'forme_id'
	];

	public function forme()
	{
		return $this->belongsTo(Forme::class);
	}

	public function dci()
	{
		return $this->belongsTo(Dci::class);
	}

	public function secretaire()
	{
		return $this->belongsTo(Secretaire::class);
	}

	public function stock()
	{
		return $this->belongsTo(Stock::class);
	}

	public function ordonnance_produits()
	{
		return $this->hasMany(OrdonnanceProduit::class);
	}

	public function produit_categories()
	{
		return $this->hasMany(ProduitCategory::class);
	}

	public function produit_classes()
	{
		return $this->hasMany(ProduitClass::class);
	}

	public function produit_sous_categories()
	{
		return $this->hasMany(ProduitSousCategory::class);
	}
}
