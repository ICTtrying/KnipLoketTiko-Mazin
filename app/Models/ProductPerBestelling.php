<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model voor de tabel ProductPerBestelling.
 */
class ProductPerBestelling extends Model
{
    protected $table = 'ProductPerBestelling';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'ProductId',
        'BestellingId',
        'Aantal',
        'UnitPrijs',
        'BTWPercentage',
        'Korting',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public function bestelling(): BelongsTo
    {
        return $this->belongsTo(Bestelling::class, 'BestellingId', 'Id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }
}
