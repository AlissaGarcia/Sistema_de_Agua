<?php

namespace Tests\Feature;

use App\Http\Requests\StoreLeituraRequest;
use App\Models\Consumidor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LeituraValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_leitura_request_accepts_numeric_values_even_when_current_is_lower_than_previous(): void
    {
        Consumidor::factory()->create([
            'id' => 1,
            'nome' => 'Consumidor Teste',
            'endereco' => 'Rua A',
            'numero_medidor' => '123456',
            'telefone' => '88999999999',
        ]);

        $data = [
            'consumidor_id' => 1,
            'mes' => '08',
            'ano' => '2026',
            'leitura_anterior' => 5000,
            'leitura_atual' => 4500,
        ];

        $validator = Validator::make($data, (new StoreLeituraRequest())->rules());

        $this->assertFalse($validator->fails(), 'A validação deve aceitar valores numéricos válidos mesmo quando a leitura atual for menor que a anterior.');
    }

    public function test_store_leitura_request_rejects_negative_leitura_atual(): void
    {
        Consumidor::factory()->create(['id' => 1]);

        $data = [
            'consumidor_id' => 1,
            'mes' => '08',
            'ano' => '2026',
            'leitura_anterior' => 1000,
            'leitura_atual' => -100,
        ];

        $validator = Validator::make($data, (new StoreLeituraRequest())->rules());

        $this->assertTrue($validator->fails(), 'A validação deve rejeitar leitura_atual negativa.');
        $this->assertArrayHasKey('leitura_atual', $validator->failed());
    }

    public function test_leitura_model_checks_if_current_reading_is_consistent_with_previous_one(): void
    {
        $leituraValida = new \App\Models\Leitura([
            'leitura_anterior' => 5000,
            'leitura_atual' => 6000,
        ]);

        $leituraInvalida = new \App\Models\Leitura([
            'leitura_anterior' => 5000,
            'leitura_atual' => 4500,
        ]);

        $this->assertTrue($leituraValida->leituraValida());
        $this->assertFalse($leituraInvalida->leituraValida());
    }
}
