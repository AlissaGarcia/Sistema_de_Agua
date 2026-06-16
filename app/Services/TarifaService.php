<?php

namespace App\Services;

use App\Models\Configuracao;

class TarifaService
{
    /**
     * Configuração de tarifas padrão
     */
    protected array $configPadrao = [
        'taxa_fixa' => 25.00,
        'limite_consumo' => 10000, // em litros (10 m³)
        'valor_excedente' => 2.00  // por 1000 litros
    ];

    /**
     * Obter configuração atual ou padrão
     */
    public function getConfiguracao(): array
    {
        $config = Configuracao::first();
        
        if (!$config) {
            return $this->configPadrao;
        }

        return [
            'taxa_fixa' => (float) $config->taxa_fixa,
            'limite_consumo' => (int) $config->limite_consumo,
            'valor_excedente' => (float) $config->valor_excedente
        ];
    }

    /**
     * Calcular tarifa baseado no consumo em litros
     * 
     * Regra de cobrança:
     * - Até 10.000 L: Taxa fixa (R$ 25,00)
     * - Acima de 10.000 L: Taxa fixa + R$ 2,00 por cada 1.000 L excedentes
     * 
     * Exemplo: 15.000 L → R$ 25,00 + R$ 10,00 (5.000 L) = R$ 35,00
     */
    public function calcularTarifa(float $consumoLitros): array
    {
        $config = $this->getConfiguracao();
        
        $consumoM3 = $consumoLitros / 1000;
        $taxaFixa = $config['taxa_fixa'];
        $limiteConsumo = $config['limite_consumo'];
        $valorExcedente = $config['valor_excedente'];
        
        $taxaExcedente = 0;
        $totalExcedente = 0;
        
        // Verificar se há excedente
        if ($consumoLitros > $limiteConsumo) {
            $litrosExcedentes = $consumoLitros - $limiteConsumo;
            $unidadesExcedentes = $litrosExcedentes / 1000;
            $taxaExcedente = $unidadesExcedentes * $valorExcedente;
        }
        
        $totalExcedente = $taxaFixa + $taxaExcedente;
        
        return [
            'consumo_m3' => round($consumoM3, 2),
            'consumo_litros' => (int) $consumoLitros,
            'taxa_fixa' => $taxaFixa,
            'taxa_excedente' => round($taxaExcedente, 2),
            'total' => round($totalExcedente, 2),
            'dentro_limite' => $consumoLitros <= $limiteConsumo,
            'limite_consumo' => $limiteConsumo,
            'litros_excedentes' => max(0, $consumoLitros - $limiteConsumo)
        ];
    }

    /**
     * Calcular tarifa a partir de leituras anterior e atual
     */
    public function calcularPorLeituras(float $leituraAnterior, float $leituraAtual): array
    {
        // Converter de m³ para litros (1 m³ = 1000 L)
        $consumoM3 = $leituraAtual - $leituraAnterior;
        $consumoLitros = $consumoM3 * 1000;
        
        if ($consumoLitros <= 0) {
            throw new \InvalidArgumentException('A leitura atual deve ser maior que a leitura anterior.');
        }
        
        return $this->calcularTarifa($consumoLitros);
    }

    /**
     * Validar configuração
     */
    public function validarConfiguracao(array $dados): array
    {
        $erros = [];
        
        if (isset($dados['taxa_fixa']) && $dados['taxa_fixa'] < 0) {
            $erros['taxa_fixa'] = 'A taxa fixa não pode ser negativa.';
        }
        
        if (isset($dados['limite_consumo']) && $dados['limite_consumo'] < 1) {
            $erros['limite_consumo'] = 'O limite de consumo deve ser maior que 0.';
        }
        
        if (isset($dados['valor_excedente']) && $dados['valor_excedente'] < 0) {
            $erros['valor_excedente'] = 'O valor do excedente não pode ser negativo.';
        }
        
        return $erros;
    }

    /**
     * Atualizar configuração
     */
    public function atualizarConfiguracao(array $dados): Configuracao
    {
        $erros = $this->validarConfiguracao($dados);
        
        if (!empty($erros)) {
            throw new \InvalidArgumentException('Dados inválidos: ' . json_encode($erros));
        }
        
        $config = Configuracao::first();
        
        if (!$config) {
            $config = new Configuracao();
        }
        
        $config->taxa_fixa = $dados['taxa_fixa'] ?? $this->configPadrao['taxa_fixa'];
        $config->limite_consumo = $dados['limite_consumo'] ?? $this->configPadrao['limite_consumo'];
        $config->valor_excedente = $dados['valor_excedente'] ?? $this->configPadrao['valor_excedente'];
        
        $config->save();
        
        return $config;
    }

    /**
     * Obter descrição formatada da tarifa
     */
    public function getDescricaoTarifa(array $calculo): string
    {
        $config = $this->getConfiguracao();
        
        if ($calculo['dentro_limite']) {
            return sprintf(
                'Consumo: %.1f m³ (%d L) → Taxa fixa: R$ %.2f',
                $calculo['consumo_m3'],
                $calculo['consumo_litros'],
                $calculo['taxa_fixa']
            );
        }
        
        return sprintf(
            'Consumo: %.1f m³ (%d L) → Taxa fixa R$ %.2f + R$ %.2f (%d L excedentes) = R$ %.2f',
            $calculo['consumo_m3'],
            $calculo['consumo_litros'],
            $calculo['taxa_fixa'],
            $calculo['taxa_excedente'],
            $calculo['litros_excedentes'],
            $calculo['total']
        );
    }
}
