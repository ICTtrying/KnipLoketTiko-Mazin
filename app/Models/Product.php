<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model voor de tabel Product.
 */
class Product extends Model
{
    protected $table = 'Product';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'CategorieId',
        'Naam',
        'Omschrijving',
        'Merk',
        'EANcode',
        'Houdbaarheidsdatum',
        'InkoopPrijs',
        'VerkoopPrijs',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'CategorieId', 'Id');
    }

    public function productRegels(): HasMany
    {
        return $this->hasMany(ProductPerBestelling::class, 'ProductId', 'Id');
    }
}
