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
    /**
     * Mapping van de exacte databasewaarden van Bestelstatus (aan elkaar
     * geschreven, zoals in het create-script) naar de leesbare labels uit de
     * wireframes. De databasewaarde blijft overal intern gebruikt (queries,
     * filters, option-values); alleen de zichtbare tekst gebruikt het label.
     *
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        'Ontvangen' => 'Ontvangen',
        'Bevestigd' => 'Bevestigd',
        'Inverwerking' => 'In verwerking',
        'Verzonden' => 'Verzonden',
        'Afgeleverd' => 'Afgeleverd',
        'Geannuleerd' => 'Geannuleerd',
    ];

    /**
     * Leesbaar label voor een statusdatabasewaarde (valt terug op de ruwe waarde).
     */
    public static function statusLabel(string $bestelstatus): string
    {
        return self::STATUS_LABELS[$bestelstatus] ?? $bestelstatus;
    }

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
