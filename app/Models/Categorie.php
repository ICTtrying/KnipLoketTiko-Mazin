<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model voor de tabel Categorie.
 */
class Categorie extends Model
{
    protected $table = 'Categorie';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'Naam',
        'Omschrijving',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public function producten(): HasMany
    {
        return $this->hasMany(Product::class, 'CategorieId', 'Id');
    }
}
