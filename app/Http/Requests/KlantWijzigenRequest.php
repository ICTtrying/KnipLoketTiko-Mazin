<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Form Request voor het wijzigen van de naam- en contactgegevens van een klant.
 *
 * De maximumlengtes volgen de kolomdefinities uit het create-script
 * (database/Createscript/SQL_Dag3.sql); Huisnummer is daar een SMALLINT.
 */
class KlantWijzigenRequest extends FormRequest
{
    /**
     * Iedere ingelogde gebruiker van deze applicatie mag deze actie uitvoeren.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * De validatieregels voor het wijzigformulier.
     *
     * @return array<string, array<int, string|Closure>>
     */
    public function rules(): array
    {
        $klantId = (int) $this->route('id', 0);

        return [
            'voornaam' => ['required', 'string', 'max:100'],
            'tussenvoegsel' => ['nullable', 'string', 'max:30'],
            'achternaam' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                // Email moet uniek zijn in de Contact tabel, behalve voor de huidige klant
                function (string $attribute, mixed $value, Closure $fail) use ($klantId): void {
                    if ($klantId === 0) {
                        return;
                    }

                    $bestaatAlders = DB::table('Contact as c')
                        ->leftJoin('KlantPerContact as kpc', 'c.Id', '=', 'kpc.ContactId')
                        ->where('c.Email', $value)
                        ->where(function ($q) use ($klantId): void {
                            $q->whereNull('kpc.KlantId')
                                ->orWhere('kpc.KlantId', '!=', $klantId);
                        })
                        ->exists();

                    if ($bestaatAlders) {
                        $fail('Het e-mailadres is al in gebruik');
                    }
                },
            ],
            'straatnaam' => ['required', 'string', 'max:100'],
            'huisnummer' => ['required', 'integer', 'between:1,32767'],
            'toevoeging' => ['nullable', 'string', 'max:10'],
            'postcode' => ['required', 'string', 'max:10'],
            'plaats' => ['required', 'string', 'max:100'],
            'mobiel' => ['required', 'string', 'max:15'],
            'bijzonderheden' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Nederlandse foutmeldingen voor de standaardregels.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'voornaam.required' => 'Het veld Voornaam is verplicht.',
            'achternaam.required' => 'Het veld Achternaam is verplicht.',
            'email.required' => 'Het veld E-mail is verplicht.',
            'email.email' => 'Het veld E-mail moet een geldig e-mailadres zijn.',
            'straatnaam.required' => 'Het veld Straatnaam is verplicht.',
            'huisnummer.required' => 'Het veld Huisnummer is verplicht.',
            'huisnummer.integer' => 'Het veld Huisnummer moet een getal zijn.',
            'huisnummer.between' => 'Het veld Huisnummer moet tussen 1 en 32767 liggen.',
            'postcode.required' => 'Het veld Postcode is verplicht.',
            'plaats.required' => 'Het veld Plaats is verplicht.',
            'mobiel.required' => 'Het veld Mobiel is verplicht.',
            'bijzonderheden.max' => 'Het veld Bijzonderheden mag maximaal 50 tekens bevatten.',
            'email' => 'Het e-mailadres is al in gebruik',
        ];
    }
}
