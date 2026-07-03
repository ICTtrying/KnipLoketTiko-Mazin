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
            $klant = Klant::with('klantPerContact.contact')->findOrFail($id);
            $contact = $klant->klantPerContact->first()?->contact;

            // Validatie
            $validated = $request->validate([
                'voornaam' => 'required|string|max:100',
                'tussenvoegsel' => 'nullable|string|max:30',
                'achternaam' => 'required|string|max:100',
                'email' => 'required|email',
                'straatnaam' => 'required|string',
                'huisnummer' => 'required|string',
                'toevoeging' => 'nullable|string',
                'postcode' => 'required|string',
                'plaats' => 'required|string',
                'mobiel' => 'required|string',
                'bijzonderheden' => 'nullable|string',
            ]);

            // Update klant
            $klant->update([
                'Voornaam' => $validated['voornaam'],
                'Tussenvoegsel' => $validated['tussenvoegsel'],
                'Achternaam' => $validated['achternaam'],
                'Bijzonderheden' => $validated['bijzonderheden'],
                'DatumGewijzigd' => now(),
            ]);

            // Update contact if it exists
            if ($contact) {
                $contact->update([
                    'ContactEmail' => $validated['email'],
                    'Straatnaam' => $validated['straatnaam'],
                    'Huisnummer' => $validated['huisnummer'],
                    'Toevoeging' => $validated['toevoeging'],
                    'Postcode' => $validated['postcode'],
                    'Plaats' => $validated['plaats'],
                    'Mobiel' => $validated['mobiel'],
                ]);
            }

            return redirect()->route('klanten.show', $klant->Id)
                ->with('success', 'Klantgegevens bijgewerkt');

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
