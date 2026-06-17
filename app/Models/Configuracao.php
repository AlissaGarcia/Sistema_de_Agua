<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    use HasFactory;

    protected $table = 'configuracoes_taxa';

    protected $fillable = [
        'taxa_fixa',
        'limite_consumo',
        'valor_excedente',
    ];

    protected $casts = [
        'taxa_fixa' => 'float',
        'limite_consumo' => 'integer',
        'valor_excedente' => 'float',
    ];

    public $timestamps = true;

    /**
     * Obtém a descrição formatada das tarifas
     */
    public function getDescricao(): string
    {
        return sprintf(
            'Taxa fixa: R$ %.2f | Limite: %d L | Excedente: R$ %.2f por 1000 L',
            $this->taxa_fixa,
            $this->limite_consumo,
            $this->valor_excedente
        );
    }
}
