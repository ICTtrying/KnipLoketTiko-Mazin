<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Basisklasse voor alle controllers.
 *
 * Bevat twee hulpmethodes die elke controller nodig heeft, zodat de logica
 * op één plek staat in plaats van in elke controller apart.
 */
abstract class Controller
{
    /**
     * Controleert of de huidige database stored procedures ondersteunt.
     *
     * Deze applicatie draait op MySQL met stored procedures. De testomgeving
     * gebruikt sqlite, dat geen stored procedures kent; daarvoor heeft elke
     * controller een gelijkwaardige query als fallback.
     */
    protected function gebruiktStoredProcedures(): bool
    {
        return in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true);
    }

    /**
     * Zet een collectie om in een pagina met resultaten (bijvoorbeeld 4 per pagina).
     */
    protected function maakPaginatie(Collection $items, Request $request, int $perPagina): LengthAwarePaginator
    {
        $huidigePagina = LengthAwarePaginator::resolveCurrentPage();
        $resultatenOpDezePagina = $items->slice(($huidigePagina - 1) * $perPagina, $perPagina)->values();

        return new LengthAwarePaginator($resultatenOpDezePagina, $items->count(), $perPagina, $huidigePagina, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }
}
