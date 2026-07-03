<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests voor ProductController (User Story 07: overzicht producten).
 */
class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_overzicht_producten_toont_alle_producten(): void
    {
        $this->seed();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertSee('Overzicht producten');
        $response->assertSee('Gevonden producten - 10 product(en)');
        $response->assertSee('Hydrating Shampoo');
        $response->assertSee('EUR 14,95');
    }

    public function test_overzicht_producten_filtert_op_categorie(): void
    {
        $this->seed();

        // Categorie 2 = Kleurproducten met drie producten in het createscript
        $response = $this->get(route('products.index', ['categorie' => 2]));

        $response->assertStatus(200);
        $response->assertSee('Gevonden producten - 3 product(en)');
        $response->assertSee('Color Creme 6.1');
        $response->assertDontSee('Hydrating Shampoo');
    }

    public function test_overzicht_producten_toont_melding_bij_categorie_zonder_producten(): void
    {
        $this->seed();

        // Categorie 4 = Accessoires zonder producten in het createscript (Wireframe-04)
        $response = $this->get(route('products.index', ['categorie' => 4]));

        $response->assertStatus(200);
        $response->assertSee('Gevonden producten - 0 product(en)');
        $response->assertSee('Er zijn geen producten bekend binnen de geselecteerde categorie');
    }

    public function test_ongeldig_categorie_filter_geeft_validatiefout(): void
    {
        $this->seed();

        $response = $this->get(route('products.index', ['categorie' => 'ongeldig']));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHasErrors('categorie');
    }
}
