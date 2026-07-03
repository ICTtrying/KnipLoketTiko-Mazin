<?php
<<<<<<< HEAD

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
=======
 
namespace App\Models;

 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

>>>>>>> 83f3a0ebd1c66b988e3fdbf58407ae4acdd44fa3

/**
 * Model voor de tabel Klant.
 *
 * De tabel heeft geen Naam-kolom; de weergavenaam wordt samengesteld uit
 * Voornaam, Tussenvoegsel en Achternaam via de VolledigeNaam-accessor.
 * Relatienummer is een gewone kolom en wordt nooit berekend.
 */
<<<<<<< HEAD
class Klant extends Model
{
    use HasFactory;

    protected $table = 'Klant';

    protected $primaryKey = 'Id';

    public $timestamps = false;

=======

class Klant extends Model
{
    use HasFactory;
 
    protected $table = 'Klant';
    protected $primaryKey = 'Id';
    public $timestamps = false;
 
>>>>>>> 83f3a0ebd1c66b988e3fdbf58407ae4acdd44fa3
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
<<<<<<< HEAD
        'DatumGewijzigd',
    ];

    /**
     * Relatie: Klant belongs to User
     */
=======
        'DatumGewijzigd'
    ];

 
    /**
     * Relatie: Klant belongs to User
     */
    public function user()

>>>>>>> 83f3a0ebd1c66b988e3fdbf58407ae4acdd44fa3

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
<<<<<<< HEAD
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }

=======

    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }
 
>>>>>>> 83f3a0ebd1c66b988e3fdbf58407ae4acdd44fa3
    /**
     * Relatie: Klant has many KlantPerContact
     */
    public function klantPerContact()
    {
        return $this->hasMany(KlantPerContact::class, 'KlantId', 'Id');
    }
<<<<<<< HEAD

=======
 
>>>>>>> 83f3a0ebd1c66b988e3fdbf58407ae4acdd44fa3
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
<<<<<<< HEAD

=======
 
>>>>>>> 83f3a0ebd1c66b988e3fdbf58407ae4acdd44fa3
    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        $name = $this->Voornaam;
<<<<<<< HEAD

        if ($this->Tussenvoegsel) {
            $name .= ' '.$this->Tussenvoegsel;
        }

        $name .= ' '.$this->Achternaam;

        return $name;
    }
}
=======
        
        if ($this->Tussenvoegsel) {
            $name .= ' ' . $this->Tussenvoegsel;
        }
        
        $name .= ' ' . $this->Achternaam;
        
        return $name;
    }
}
>>>>>>> 83f3a0ebd1c66b988e3fdbf58407ae4acdd44fa3
