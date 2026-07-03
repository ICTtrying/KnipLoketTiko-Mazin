<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model voor de tabel Bestelling.
 */
class Bestelling extends Model
{
    protected $table = 'Bestelling';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'KlantId',
        'BestelNummer',
        'Omschrijving',
        'Datum',
        'Tijd',
        'Bestelstatus',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public function klant(): BelongsTo
    {
        return $this->belongsTo(Klant::class, 'KlantId', 'Id');
    }

    public function producten(): HasMany
    {
        return $this->hasMany(ProductPerBestelling::class, 'BestellingId', 'Id');
    }
}
