<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Behandeling extends Model
{
    protected $table = 'Behandeling';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'Naam',
        'Omschrijving',
        'DuurMinuten',
        'Prijs',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd',
    ];

    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';
}
