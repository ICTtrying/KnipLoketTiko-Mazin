<?php

namespace App\Http\Controllers;

use App\Models\Klant;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KlantController extends Controller
{
    /**
     * Index - Show all klanten (User Story 1: Read)
     * Uses Eloquent to fetch klanten with contact information
     */
    public function index(Request $request)
    {
        try { 
            // Get postcode filter from request
            $postcode = $request->input('postcode', null);

            // Query klanten with their contact information
            $query = Klant::with('klantPerContact.contact')
                ->where('IsActief', 1);

            // Filter by postcode if provided
            if ($postcode) {
                $query->whereHas('klantPerContact.contact', function ($q) use ($postcode) {
                    $q->where('Postcode', 'like', "%$postcode%");
                });
            }

            $klanten = $query->get()->map(function ($klant) {
                // Transform to match view expectations
                $contact = $klant->klantPerContact->first()?->contact;
                return (object) [
                    'Id' => $klant->Id,
                    'Voornaam' => $klant->Voornaam,
                    'Achternaam' => $klant->Achternaam,
                    'Relatienummer' => $klant->Relatienummer,
                    'Straatnaam' => $contact?->Straatnaam ?? '-',
                    'Huisnummer' => $contact?->Huisnummer ?? '-',
                    'Postcode' => $contact?->Postcode ?? '-',
                    'Plaats' => $contact?->Plaats ?? '-',
                    'Mobiel' => $contact?->Mobiel ?? '-',
                    'ContactEmail' => $contact?->ContactEmail ?? '-',
                ];
            });

            // Check if results are empty
            $message = $postcode && empty($klanten)
                ? 'Er zijn geen klanten bekent die de geselecteerde postcode hebben'
                : null;

            return view('klanten.index', [
                'klanten' => $klanten,
                'postcode' => $postcode,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            // Log the error for technical logging
            \Log::error('KlantController@index error: ' . $e->getMessage());

            return redirect()->route('klanten.index')
                ->with('error', 'Er is een fout opgetreden bij het ophalen van klanten');
        }
    }
 
    /**
     * Show - Display klant details (User Story 2: Start Update)
     */
    public function show($id)
    {
        try {
            $klant = Klant::with('klantPerContact.contact')->findOrFail($id);
            $contact = $klant->klantPerContact->first()?->contact;

            // Transform to match view expectations
            $klantData = (object) [
                'Id' => $klant->Id,
                'Voornaam' => $klant->Voornaam,
                'Tussenvoegsel' => $klant->Tussenvoegsel,
                'Achternaam' => $klant->Achternaam,
                'Relatienummer' => $klant->Relatienummer,
                'ContactEmail' => $contact?->ContactEmail ?? '-',
                'AccountEmail' => $contact?->ContactEmail ?? '-',
                'Straatnaam' => $contact?->Straatnaam ?? '-',
                'Huisnummer' => $contact?->Huisnummer ?? '-',
                'Toevoeging' => $contact?->Toevoeging,
                'Postcode' => $contact?->Postcode ?? '-',
                'Plaats' => $contact?->Plaats ?? '-',
                'Mobiel' => $contact?->Mobiel ?? '-',
                'Bijzonderheden' => $klant->Bijzonderheden,
            ];

            return view('klanten.show', ['klant' => $klantData]);

        } catch (\Exception $e) {
            \Log::error('KlantController@show error: ' . $e->getMessage());

            return redirect()->route('klanten.index')
                ->with('error', 'Klant niet gevonden');
        }
    }

    /**
     * Edit - Show edit form (User Story 2: Update form)
     */
    public function edit($id)
    {
        try {
            $klant = Klant::with('klantPerContact.contact')->findOrFail($id);
            $contact = $klant->klantPerContact->first()?->contact;

            // Transform to match view expectations
            $klantData = (object) [
                'Id' => $klant->Id,
                'ContactId' => $contact?->Id,
                'Voornaam' => $klant->Voornaam,
                'Tussenvoegsel' => $klant->Tussenvoegsel,
                'Achternaam' => $klant->Achternaam,
                'Relatienummer' => $klant->Relatienummer,
                'ContactEmail' => $contact?->ContactEmail ?? '',
                'Straatnaam' => $contact?->Straatnaam ?? '',
                'Huisnummer' => $contact?->Huisnummer ?? '',
                'Toevoeging' => $contact?->Toevoeging,
                'Postcode' => $contact?->Postcode ?? '',
                'Plaats' => $contact?->Plaats ?? '',
                'Mobiel' => $contact?->Mobiel ?? '',
                'Bijzonderheden' => $klant->Bijzonderheden,
            ];

            return view('klanten.edit', ['klant' => $klantData]);

        } catch (\Exception $e) {
            \Log::error('KlantController@edit error: ' . $e->getMessage());

            return redirect()->route('klanten.index')
                ->with('error', 'Klant niet gevonden');
        }
    }

    /**
     * Update - Save klant changes (User Story 2: Update)
     */
public function update(Request $request, $id)
{
    try {
        // 1. Validatie
        $validated = $request->validate([
            'email' => 'required|email|unique:Contact,Email,' . $request->contact_id . ',Id'
        ], [
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'email.unique' => 'Het e-mailadres is al in gebruik'
        ]);

        // 2. Haal de huidige klant op uit de database om te vergelijken
        // Gebruik de methode die ook in je 'show' of 'edit' staat
        $klant = DB::select('CALL sp_get_klant_by_id(?)', [$id])[0];

        // 3. Controleer of het e-mailadres écht veranderd is
        if ($request->email === $klant->ContactEmail) {
            return back()
                ->with('error', 'Klantgegevens zijn niet bijgewerkt')
                ->withInput();
        }

        // 4. Als het wel veranderd is, voer de update uit
        $contactId = $request->input('contact_id');
        
        DB::statement(
            'CALL sp_update_klant_email(?, ?, @p_success, @p_message)',
            [$contactId, $validated['email']]
        );
        
        $result = DB::select('SELECT @p_success as success, @p_message as message');

        if ($result[0]->success == 1) {
            return redirect()->route('klanten.index')
                ->with('success', 'Klantgegevens bijgewerkt');
        } else {
            return back()
                ->withErrors(['email' => $result[0]->message])
                ->withInput();
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()
            ->withErrors($e->errors())
            ->withInput();

    } catch (\Exception $e) {
        \Log::error('KlantController@update error: ' . $e->getMessage());
        return back()
            ->with('error', 'Klantgegevens zijn niet bijgewerkt')
            ->withInput();
    }
}
}
