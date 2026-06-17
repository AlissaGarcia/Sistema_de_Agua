<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consumidor extends Model
{
    use HasFactory;

    protected $table = 'consumidores';

    protected $fillable = [
        'nome',
        'endereco',
        'numero_medidor',
        'telefone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com Leituras
     */
    public function leituras(): HasMany
    {
        return $this->hasMany(Leitura::class);
    }

    /**
     * Relacionamento com Faturas
     */
    public function faturas(): HasMany
    {
        return $this->hasMany(Fatura::class);
    }

    /**
     * Obter última leitura registrada
     */
    public function ultimaLeitura(): ?Leitura
    {
        return $this->leituras()->latest()->first();
    }

    /**
     * Verificar se está ativo
     */
    public function isAtivo(): bool
    {
        return true;
    }

    /**
     * Obter faturas pendentes
     */
    public function faturasPendentes()
    {
        return $this->faturas()->where('status', 'pendente');
    }

    /**
     * Obter total devido
     */
    public function totalDevido(): float
    {
        return $this->faturasPendentes()->sum('total');
    }
}
