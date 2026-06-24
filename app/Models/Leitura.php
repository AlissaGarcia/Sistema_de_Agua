<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Leitura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'consumidor_id',
        'mes',
        'ano',
        'leitura_anterior',
        'leitura_atual',
        'consumo_m3',
        'consumo_litros',
    ];

    protected $casts = [
        'leitura_anterior' => 'float',
        'leitura_atual' => 'float',
        'consumo_m3' => 'float',
        'consumo_litros' => 'integer',
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
     * Relacionamento com Fatura
     */
    public function fatura(): HasOne
    {
        return $this->hasOne(Fatura::class);
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
     * Calcular consumo em litros
     */
    public function calcularConsumo(): int
    {
        return (int)(($this->leitura_atual - $this->leitura_anterior) * 1000);
    }
}
