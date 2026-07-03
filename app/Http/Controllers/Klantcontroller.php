<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
 
class KlantController extends Controller
{
    /**
     * Index - Show all klanten (User Story 1: Read)
     * Calls stored procedure sp_get_klanten
     */
    public function index(Request $request)
    {
        try {
            // Get postcode filter from request
            $postcode = $request->input('postcode', null);
 
            // Call stored procedure with 3+ JOINS
            $klanten = DB::select('CALL sp_get_klanten(?)', [$postcode]);
 
            // Check if results are empty
            if (empty($klanten)) {
                $message = 'Er zijn geen klanten bekent die de geselecteerde postcode hebben';
            } else {
                $message = null;
            }
 
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
     * Calls stored procedure sp_get_klant_by_id
     */
    public function show($id)
    {
        try {
            // Call stored procedure to get single klant with details
            $klanten = DB::select('CALL sp_get_klant_by_id(?)', [$id]);
 
            if (empty($klanten)) {
                return redirect()->route('klanten.index')
                    ->with('error', 'Klant niet gevonden');
            }
 
            $klant = $klanten[0];
 
            return view('klanten.show', ['klant' => $klant]);
 
        } catch (\Exception $e) {
            \Log::error('KlantController@show error: ' . $e->getMessage());
 
            return redirect()->route('klanten.index')
                ->with('error', 'Er is een fout opgetreden');
        }
    }
 
    /**
     * Edit - Show edit form (User Story 2: Update form)
     * Calls stored procedure sp_get_klant_by_id
     */
    public function edit($id)
    {
        try {
            // Get klant details
            $klanten = DB::select('CALL sp_get_klant_by_id(?)', [$id]);
 
            if (empty($klanten)) {
                return redirect()->route('klanten.index')
                    ->with('error', 'Klant niet gevonden');
            }
 
            $klant = $klanten[0];
 
            return view('klanten.edit', ['klant' => $klant]);
 
        } catch (\Exception $e) {
            \Log::error('KlantController@edit error: ' . $e->getMessage());
 
            return redirect()->route('klanten.index')
                ->with('error', 'Er is een fout opgetreden');
        }
    }
 
    /**
     * Update - Save klant changes (User Story 2: Update)
     * Calls stored procedure sp_update_klant_email
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
