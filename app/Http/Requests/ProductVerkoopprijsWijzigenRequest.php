<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Form Request voor het wijzigen van de verkoopprijs van een product
 * binnen een behandeling (User Story 06).
 *
 * Bevat de custom validatieregel dat de nieuwe verkoopprijs minimaal
 * 30 procent boven de inkoopprijs van het product moet liggen.
 */
class ProductVerkoopprijsWijzigenRequest extends FormRequest
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
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'nieuwe_verkoopprijs' => [
                'required',
                'numeric',
                'min:0.01',
                // Businessregel: minimaal 30 procent boven de inkoopprijs
                function (string $attribute, mixed $value, Closure $fail): void {
                    $inkoopprijs = DB::table('Product')
                        ->where('Id', (int) $this->route('product'))
                        ->where('IsActief', 1)
                        ->value('InkoopPrijs');

                    if ($inkoopprijs !== null && is_numeric($value) && (float) $value < (float) $inkoopprijs * 1.30) {
                        $fail('Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen');
                    }
                },
            ],
            'opmerking' => ['nullable', 'string', 'max:255'],
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
            'nieuwe_verkoopprijs.required' => 'Het veld Nieuwe verkoopprijs is verplicht.',
            'nieuwe_verkoopprijs.numeric' => 'Het veld Nieuwe verkoopprijs moet een getal zijn.',
            'nieuwe_verkoopprijs.min' => 'Het veld Nieuwe verkoopprijs moet groter zijn dan 0.',
            'opmerking.max' => 'Het veld Opmerking mag maximaal 255 tekens bevatten.',
        ];
    }

    /**
     * Toon bij een validatiefout de banner uit de user story bovenaan de pagina.
     */
    protected function failedValidation(Validator $validator): void
    {
        $this->session()->flash('foutmelding', 'Gegevens niet bijgewerkt');

        parent::failedValidation($validator);
    }
}
