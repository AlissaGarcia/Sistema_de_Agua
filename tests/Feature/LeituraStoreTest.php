<?php

namespace Tests\Feature;

use App\Models\Consumidor;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeituraStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_teste_1_dados_validos_registra_leitura_e_fatura(): void
    {
        $user = User::factory()->create(['role' => 'leiturista']);
        $consumidor = Consumidor::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('leituras.store'), [
                'consumidor_id' => $consumidor->id,
                'mes' => '08',
                'ano' => '2026',
                'leitura_anterior' => 1000,
                'leitura_atual' => 1008,
            ]);

        $response->assertRedirect(route('faturas.show', 1));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('leituras', [
            'consumidor_id' => $consumidor->id,
            'mes' => '08',
            'ano' => '2026',
            'leitura_anterior' => 1000,
            'leitura_atual' => 1008,
            'consumo_m3' => 8.0,
            'consumo_litros' => 8000,
        ]);

        $this->assertDatabaseHas('faturas', [
            'consumidor_id' => $consumidor->id,
            'mes' => '08',
            'ano' => '2026',
            'consumo_m3' => 8.0,
            'consumo_litros' => 8000,
            'total' => 25.0,
        ]);
    }

    public function test_teste_2_dados_invalidos_leitura_atual_negativa_rejeitada(): void
    {
        $user = User::factory()->create(['role' => 'leiturista']);
        $consumidor = Consumidor::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('leituras.create'))
            ->post(route('leituras.store'), [
                'consumidor_id' => $consumidor->id,
                'mes' => '08',
                'ano' => '2026',
                'leitura_anterior' => 1000,
                'leitura_atual' => -100,
            ]);

        $response->assertRedirect(route('leituras.create'));
        $this->assertDatabaseCount('leituras', 0);
        $this->assertDatabaseCount('faturas', 0);
    }

    public function test_store_current_reading_lower_than_previous_is_rejected(): void
    {
        $user = User::factory()->create(['role' => 'leiturista']);
        $consumidor = Consumidor::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('leituras.create'))
            ->post(route('leituras.store'), [
                'consumidor_id' => $consumidor->id,
                'mes' => '08',
                'ano' => '2026',
                'leitura_anterior' => 10000,
                'leitura_atual' => 9000,
            ]);

        $response->assertRedirect(route('leituras.create'));
        $this->assertDatabaseCount('leituras', 0);
        $this->assertDatabaseCount('faturas', 0);
    }
}
