<?php

namespace App\Http\Controllers;

use App\Http\Requests\KlantWijzigenRequest;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

/**
 * Controller voor het klantenoverzicht, de detailpagina en het wijzigen
 * van klantgegevens. De data komt via stored procedures uit MySQL;
 * op sqlite (testomgeving) wordt een gelijkwaardige query-fallback gebruikt.
 */
class KlantController extends Controller
{
    /**
     * Overzicht van alle actieve klanten, optioneel gefilterd op postcode.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $postcode = trim((string) $request->input('postcode', ''));
            $postcode = $postcode === '' ? null : $postcode;

            Log::info('Klantenoverzicht opgevraagd', ['postcode' => $postcode]);

            $klanten = $this->haalKlantenOp($postcode)->map(fn (object $klant): object => (object) [
                'Id' => $klant->Id,
                'Voornaam' => $klant->Voornaam,
                'Achternaam' => $klant->Achternaam,
                'Relatienummer' => $klant->Relatienummer,
                'Straatnaam' => $klant->Straatnaam ?? '-',
                'Huisnummer' => $klant->Huisnummer ?? '-',
                'Postcode' => $klant->Postcode ?? '-',
                'Plaats' => $klant->Plaats ?? '-',
                'Mobiel' => $klant->Mobiel ?? '-',
                'Email' => $klant->Email ?? '-',
            ]);

            return view('klanten.index', [
                'klanten' => $klanten,
                'postcode' => $postcode,
                'message' => $postcode !== null && $klanten->isEmpty()
                    ? 'Er zijn geen klanten bekent die de geselecteerde postcode hebben'
                    : null,
            ]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen klantenoverzicht', ['error' => $e->getMessage()]);

            return back()->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de klanten.');
        }
    }

    /**
     * Detailpagina van één klant.
     */
    public function show(int $id): View|RedirectResponse
    {
        try {
            Log::info('Klantdetail opgevraagd', ['klant_id' => $id]);

            $klant = $this->haalKlantOp($id);

            if ($klant === null) {
                return redirect()->route('klanten.index')->with('foutmelding', 'Klant niet gevonden');
            }

            return view('klanten.show', ['klant' => (object) [
                'Id' => $klant->Id,
                'Voornaam' => $klant->Voornaam,
                'Tussenvoegsel' => $klant->Tussenvoegsel,
                'Achternaam' => $klant->Achternaam,
                'Relatienummer' => $klant->Relatienummer,
                'Email' => $klant->Email ?? '-',
                'Straatnaam' => $klant->Straatnaam ?? '-',
                'Huisnummer' => $klant->Huisnummer ?? '-',
                'Toevoeging' => $klant->Toevoeging,
                'Postcode' => $klant->Postcode ?? '-',
                'Plaats' => $klant->Plaats ?? '-',
                'Mobiel' => $klant->Mobiel ?? '-',
                'Bijzonderheden' => $klant->Bijzonderheden,
            ]]);
        } catch (Throwable $e) {
            Log::error('Fout bij ophalen klantdetail', ['klant_id' => $id, 'error' => $e->getMessage()]);

            return redirect()->route('klanten.index')->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de klant.');
        }
    }

    /**
     * Wijzigformulier van één klant.
     */
    public function edit(int $id): View|RedirectResponse
    {
        try {
            Log::info('Klant wijzig-formulier geopend', ['klant_id' => $id]);

            $klant = $this->haalKlantOp($id);

            if ($klant === null) {
                return redirect()->route('klanten.index')->with('foutmelding', 'Klant niet gevonden');
            }

            return view('klanten.edit', ['klant' => (object) [
                'Id' => $klant->Id,
                'Voornaam' => $klant->Voornaam,
                'Tussenvoegsel' => $klant->Tussenvoegsel,
                'Achternaam' => $klant->Achternaam,
                'Relatienummer' => $klant->Relatienummer,
                'Email' => $klant->Email ?? '',
                'Straatnaam' => $klant->Straatnaam ?? '',
                'Huisnummer' => $klant->Huisnummer ?? '',
                'Toevoeging' => $klant->Toevoeging,
                'Postcode' => $klant->Postcode ?? '',
                'Plaats' => $klant->Plaats ?? '',
                'Mobiel' => $klant->Mobiel ?? '',
                'Bijzonderheden' => $klant->Bijzonderheden,
            ]]);
        } catch (Throwable $e) {
            Log::error('Fout bij openen wijzig-formulier klant', ['klant_id' => $id, 'error' => $e->getMessage()]);

            return redirect()->route('klanten.index')->with('foutmelding', 'Er is een fout opgetreden bij het ophalen van de klant.');
        }
    }

    /**
     * Sla de gewijzigde klantgegevens op via de stored procedure.
     */
    public function update(KlantWijzigenRequest $request, int $id): RedirectResponse
    {
        try {
            $gevalideerd = $request->validated();

            Log::info('Poging tot wijzigen klantgegevens', ['klant_id' => $id]);

            [$succes, $foutmelding] = $this->wijzigKlant($id, $gevalideerd);

            if (! $succes) {
                Log::warning('Wijzigen klantgegevens geweigerd', ['klant_id' => $id, 'reden' => $foutmelding]);

                // withErrors zorgt dat de "Klantgegevens zijn niet bijgewerkt"-banner
                // op het wijzigformulier verschijnt, gelijk aan het gedrag bij validatiefouten
                return back()
                    ->withInput()
                    ->withErrors(['email' => $foutmelding ?? 'Klantgegevens zijn niet bijgewerkt']);
            }

            Log::info('Klantgegevens succesvol gewijzigd', ['klant_id' => $id]);

            return redirect()->route('klanten.index')->with('succesmelding', 'Klantgegevens bijgewerkt');
        } catch (Throwable $e) {
            Log::error('Fout bij wijzigen klantgegevens', ['klant_id' => $id, 'error' => $e->getMessage()]);

            return back()->withInput()->with('foutmelding', 'Er is een onverwachte fout opgetreden.');
        }
    }

    /**
     * Haal het klantenoverzicht op via stored procedure of query-fallback.
     */
    private function haalKlantenOp(?string $postcode): Collection
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_klanten_overzicht(?)', [$postcode]));
        }

        return $this->klantMetContactQuery()
            ->when($postcode !== null, fn (Builder $query) => $query->where('c.Postcode', 'like', '%'.$postcode.'%'))
            ->orderBy('k.Id')
            ->get();
    }

    /**
     * Haal één klant met contactgegevens op via stored procedure of query-fallback.
     */
    private function haalKlantOp(int $id): ?object
    {
        if ($this->gebruiktStoredProcedures()) {
            return collect(DB::select('CALL sp_klant_ophalen(?)', [$id]))->first();
        }

        return $this->klantMetContactQuery()
            ->addSelect(['k.Bijzonderheden', 'c.Id as ContactId'])
            ->where('k.Id', $id)
            ->first();
    }

    /**
     * Werk de klant- en contactgegevens bij via stored procedure of query-fallback.
     * De fallback spiegelt de stored procedure voor de sqlite-testomgeving.
     *
     * @param  array<string, mixed>  $gegevens
     * @return array{0: bool, 1: ?string}
     */
    private function wijzigKlant(int $id, array $gegevens): array
    {
        if ($this->gebruiktStoredProcedures()) {
            DB::statement('SET @succes = 0');
            DB::statement('SET @foutmelding = NULL');
            DB::statement('CALL sp_klant_wijzigen(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @succes, @foutmelding)', [
                $id,
                $gegevens['voornaam'],
                $gegevens['tussenvoegsel'] ?? null,
                $gegevens['achternaam'],
                $gegevens['bijzonderheden'] ?? null,
                $gegevens['email'],
                $gegevens['straatnaam'],
                $gegevens['huisnummer'],
                $gegevens['toevoeging'] ?? null,
                $gegevens['postcode'],
                $gegevens['plaats'],
                $gegevens['mobiel'],
            ]);

            $resultaat = DB::select('SELECT @succes AS succes, @foutmelding AS foutmelding');

            return [
                (bool) ($resultaat[0]->succes ?? false),
                $resultaat[0]->foutmelding ?? null,
            ];
        }

        $bestaat = DB::table('Klant')->where('Id', $id)->where('IsActief', 1)->exists();

        if (! $bestaat) {
            return [false, 'Klant niet gevonden'];
        }

        DB::transaction(function () use ($id, $gegevens): void {
            DB::table('Klant')
                ->where('Id', $id)
                ->update([
                    'Voornaam' => $gegevens['voornaam'],
                    'Tussenvoegsel' => $gegevens['tussenvoegsel'] ?? null,
                    'Achternaam' => $gegevens['achternaam'],
                    // Bijzonderheden is NOT NULL in het create-script; leeg veld wordt een lege string
                    'Bijzonderheden' => $gegevens['bijzonderheden'] ?? '',
                    'DatumGewijzigd' => now(),
                ]);

            $contactId = DB::table('KlantPerContact')
                ->where('KlantId', $id)
                ->where('IsActief', 1)
                ->orderBy('Id')
                ->value('ContactId');

            // Contactgegevens alleen bijwerken als er een actieve contactkoppeling bestaat
            if ($contactId !== null) {
                DB::table('Contact')
                    ->where('Id', $contactId)
                    ->update([
                        'Email' => $gegevens['email'],
                        'Straatnaam' => $gegevens['straatnaam'],
                        'Huisnummer' => $gegevens['huisnummer'],
                        'Toevoeging' => $gegevens['toevoeging'] ?? null,
                        'Postcode' => $gegevens['postcode'],
                        'Plaats' => $gegevens['plaats'],
                        'Mobiel' => $gegevens['mobiel'],
                        'DatumGewijzigd' => now(),
                    ]);
            }
        });

        return [true, null];
    }

    /**
     * Basisquery voor de sqlite-fallback: actieve klanten met hun actieve contact,
     * gelijk aan de JOIN-structuur in de stored procedures.
     */
    private function klantMetContactQuery(): Builder
    {
        return DB::table('Klant as k')
            ->leftJoin('KlantPerContact as kpc', function ($join): void {
                $join->on('kpc.KlantId', '=', 'k.Id')->where('kpc.IsActief', 1);
            })
            ->leftJoin('Contact as c', function ($join): void {
                $join->on('c.Id', '=', 'kpc.ContactId')->where('c.IsActief', 1);
            })
            ->where('k.IsActief', 1)
            ->select([
                'k.Id',
                'k.Voornaam',
                'k.Tussenvoegsel',
                'k.Achternaam',
                'k.Relatienummer',
                'c.Straatnaam',
                'c.Huisnummer',
                'c.Toevoeging',
                'c.Postcode',
                'c.Plaats',
                'c.Mobiel',
                'c.Email',
            ]);
    }
}
