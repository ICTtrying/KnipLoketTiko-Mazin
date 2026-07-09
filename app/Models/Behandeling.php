<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    
    public function sp_PakAlleBehandelingen(string $naam)
    {
        return DB::select('CALL sp_behandelingen_overzicht(?)', [$naam]);
    }

    public function sp_PakProductenPerBehandeling(int $behandelingId)
    {
        return DB::select('CALL sp_producten_per_behandeling(?)', [$behandelingId]);
    }

    public function sp_PakProductDetail(int $productId)
    {
        return DB::selectOne('CALL sp_product_detail(?)', [$productId]);
    }

    public function sp_UpdateProductPrijs(int $productId, float $prijs, string $opmerking)
    {
        return DB::select('CALL sp_product_verkoopprijs_bijwerken(?, ?, ?)', [$productId, $prijs, $opmerking]);
    }
}