<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Exameneis 4 & 7: Volgt MVC-structuur en codeconventies (PSR-12)
 * Exameneis 6: Passende naamgeving functies
 */
class Product extends Model
{
    // Koppel handmatig aan de juiste PascalCase tabel uit het createscript
    protected $table = 'Product';
    protected $primaryKey = 'Id';
    
    // Uitzetten omdat we geen standaardtijden (created_at/updated_at) gebruiken, maar handmatige
    public $timestamps = false;

    /**
     * Haalt alle actieve producten op (optioneel gefilterd op CategorieId) via de Stored Procedure.
     * Exameneis 5 & 8: Stored procedure met veilige parameter bindings tegen SQL Injection
     */
    public static function getAllProducten(?int $categorieId = null): array
    {
        return DB::select('CALL Sp_GetAllProducten(?)', [$categorieId]);
    }

    /**
     * Haalt alle actieve categorieën op ten behoeve van het filter-dropdownmenu.
     */
    public static function getActieveCategorieen(): array
    {
        return DB::select('SELECT Id, Naam FROM Categorie WHERE IsActief = 1 ORDER BY Naam ASC');
    }
}