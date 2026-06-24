<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fatura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'consumidor_id',
        'leitura_id',
        'mes',
        'ano',
        'leitura_anterior',
        'leitura_atual',
        'consumo_m3',
        'consumo_litros',
        'taxa_fixa',
        'taxa_excedente',
        'total',
        'status',
        'data_vencimento',
        'data_pagamento',
    ];

    protected $casts = [
        'leitura_anterior' => 'float',
        'leitura_atual' => 'float',
        'consumo_m3' => 'float',
        'consumo_litros' => 'integer',
        'taxa_fixa' => 'float',
        'taxa_excedente' => 'float',
        'total' => 'float',
        'data_vencimento' => 'datetime',
        'data_pagamento' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com Consumidor
     */
    public function consumidor(): BelongsTo
    {
        return $this->belongsTo(Consumidor::class)->withTrashed();
    }

    /**
     * Relacionamento com Leitura
     */
    public function leitura(): BelongsTo
    {
        return $this->belongsTo(Leitura::class)->withTrashed();
    }

    /**
     * Obter período em formato legível
     */
    public function getPeriodo(): string
    {
        $meses = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];

        return $meses[$this->mes] . '/' . $this->ano;
    }

    /**
     * Verificar se está paga
     */
    public function isPaga(): bool
    {
        return $this->status === 'pago';
    }

    /**
     * Verificar se está pendente
     */
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    /**
     * Marcar como paga
     */
    public function marcarComoPaga(): void
    {
        $this->update([
            'status' => 'pago',
            'data_pagamento' => now(),
        ]);
    }

    /**
     * Obter descrição formatada da fatura
     */
    public function getDescricao(): string
    {
        $excedente = $this->taxa_excedente > 0 ? " + R$ " . number_format($this->taxa_excedente, 2, ',', '.') . " excedentes" : "";
        
        return sprintf(
            "R$ %.2f%s = R$ %.2f",
            $this->taxa_fixa,
            $excedente,
            $this->total
        );
    }
}
