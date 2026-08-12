<?php

namespace Tests\Feature;

use App\Services\FaturaCalculatorService;
use Tests\TestCase;

class FaturaCalculatorServiceTest extends TestCase
{
    public function test_calcula_valor_da_fatura_ate_10_m3(): void
    {
        $service = new FaturaCalculatorService();

        $resultado = $service->calcular(8);

        $this->assertSame(25.0, $resultado['total']);
        $this->assertSame(25.0, $resultado['taxa_fixa']);
        $this->assertSame(0.0, $resultado['valor_excedente']);
    }

    public function test_calcula_valor_da_fatura_entre_10_e_20_m3(): void
    {
        $service = new FaturaCalculatorService();

        $resultado = $service->calcular(15);

        $this->assertSame(35.0, $resultado['total']);
        $this->assertSame(25.0, $resultado['taxa_fixa']);
        $this->assertSame(10.0, $resultado['valor_excedente']);
    }

    public function test_calcula_valor_da_fatura_acima_de_20_m3(): void
    {
        $service = new FaturaCalculatorService();

        $resultado = $service->calcular(25);

        $this->assertSame(60.0, $resultado['total']);
        $this->assertSame(25.0, $resultado['taxa_fixa']);
        $this->assertSame(35.0, $resultado['valor_excedente']);
    }
}
