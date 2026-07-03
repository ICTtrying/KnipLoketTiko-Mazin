<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model voor de tabel Klant.
 *
 * De tabel heeft geen Naam-kolom; de weergavenaam wordt samengesteld uit
 * Voornaam, Tussenvoegsel en Achternaam via de VolledigeNaam-accessor.
 * Relatienummer is een gewone kolom en wordt nooit berekend.
 */
class Klant extends Model
{
    use HasFactory;

    protected $table = 'Klant';

    protected $primaryKey = 'Id';

    public $timestamps = false;

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

    /**
     * Relatie: Klant belongs to User
     */

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

    /**
     * Relatie: Klant has many KlantPerContact
     */
    public function klantPerContact()
    {
        return $this->hasMany(KlantPerContact::class, 'KlantId', 'Id');
    }

    /**
     * Get contact through KlantPerContact
     */
    public function contacts()
    {
        return $this->hasManyThrough(
            Contact::class,
            KlantPerContact::class,
            'KlantId',
            'Id',
            'Id',
            'ContactId'
        );
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        $name = $this->Voornaam;

        if ($this->Tussenvoegsel) {
            $name .= ' '.$this->Tussenvoegsel;
        }

        $name .= ' '.$this->Achternaam;

        return $name;
    }
}
