<?php

namespace App\Services;

class FaturaCalculatorService
{
    public function calcular(float $consumoM3): array
    {
        $taxaFixa = 25.00;
        $valorExcedente = 0.00;

        if ($consumoM3 <= 10) {
            $total = $taxaFixa;
        } elseif ($consumoM3 <= 20) {
            $excedente = max(0, $consumoM3 - 10);
            $valorExcedente = $excedente * 2.00;
            $total = $taxaFixa + $valorExcedente;
        } else {
            $faixa10a20 = 10;
            $faixaAcima20 = max(0, $consumoM3 - 20);
            $valorExcedente = ($faixa10a20 * 2.00) + ($faixaAcima20 * 3.00);
            $total = $taxaFixa + $valorExcedente;
        }

        return [
            'taxa_fixa' => round($taxaFixa, 2),
            'valor_excedente' => round($valorExcedente, 2),
            'total' => round($total, 2),
            'consumo_m3' => round($consumoM3, 2),
        ];
    }
}
