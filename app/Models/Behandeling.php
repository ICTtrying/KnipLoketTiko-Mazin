<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model voor de tabel Behandeling.
 *
 * Wordt gebruikt als representatie van een behandeling; het ophalen van
 * overzichts- en detaildata loopt via stored procedures in de controller.
 */
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
