<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model voor de tabel Klant.
 *
 * De tabel heeft geen Naam-kolom; de weergavenaam wordt samengesteld uit
 * Voornaam, Tussenvoegsel en Achternaam via de VolledigeNaam-accessor.
 * Relatienummer is een gewone kolom en wordt nooit berekend.
 */
class Klant extends Model
{
    protected $table = 'Klant';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'UserId',
        'Voornaam',
        'Tussenvoegsel',
        'Achternaam',
        'Relatienummer',
        'Bijzonderheden',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    /**
     * Volledige weergavenaam: Voornaam + Tussenvoegsel (indien aanwezig) + Achternaam.
     */
    protected function volledigeNaam(): Attribute
    {
        return Attribute::get(fn (): string => collect([$this->Voornaam, $this->Tussenvoegsel, $this->Achternaam])
            ->filter()
            ->implode(' '));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }

    public function bestellingen(): HasMany
    {
        return $this->hasMany(Bestelling::class, 'KlantId', 'Id');
    }
}
