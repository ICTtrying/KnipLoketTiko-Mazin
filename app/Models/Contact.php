<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model voor de tabel Contact.
 */
class Contact extends Model
{
    protected $table = 'Contact';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'Straatnaam',
        'Huisnummer',
        'Toevoeging',
        'Postcode',
        'Plaats',
        'Email',
        'Mobiel',
        'IsActief',
        'Opmerking',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';
}
