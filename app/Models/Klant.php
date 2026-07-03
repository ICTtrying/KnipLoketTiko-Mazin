<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model voor de tabel Klant.
 */
class Klant extends Model
{
    protected $table = 'Klant';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'UserId',
        'Naam',
        'Telefoonnummer',
        'WensenAllergieen',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }

    public function bestellingen(): HasMany
    {
        return $this->hasMany(Bestelling::class, 'KlantId', 'Id');
    }
}
