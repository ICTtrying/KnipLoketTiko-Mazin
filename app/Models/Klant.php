<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model voor de tabel Klant.
 *
 * De tabel heeft geen Naam-kolom; de weergavenaam wordt samengesteld uit
 * Voornaam, Tussenvoegsel en Achternaam via de volledigeNaam-accessor.
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
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }

    /**
     * Volledige weergavenaam: Voornaam, Tussenvoegsel en Achternaam samengevoegd.
     */
    protected function volledigeNaam(): Attribute
    {
        return Attribute::get(fn (): string => collect([$this->Voornaam, $this->Tussenvoegsel, $this->Achternaam])
            ->filter()
            ->implode(' '));
    }
}
