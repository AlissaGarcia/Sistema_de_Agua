@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Detalhes da fatura</h2>
    <div style="display: flex; gap: 0.5rem;">
        @php
            $telefone = preg_replace('/\D+/', '', $fatura->consumidor->telefone ?? '');
            $mensagem = "Olá, {$fatura->consumidor->nome}! Segue o consumo de {$fatura->getPeriodo()}:\n" .
                "Medidor: {$fatura->consumidor->numero_medidor}\n" .
                "Leitura anterior: " . number_format($fatura->leitura_anterior, 3, ',', '.') . " m3 → Leitura atual: " . number_format($fatura->leitura_atual, 3, ',', '.') . " m3\n" .
                "Consumo: " . number_format($fatura->consumo_m3, 3, ',', '.') . " m3 (" . number_format($fatura->consumo_litros, 0, ',', '.') . " litros)\n" .
                "Valor da fatura: R$ " . number_format($fatura->total, 2, ',', '.') . "\n" .
                "Att, Associação Comunitária";
            $whatsappLink = $telefone ? 'https://wa.me/55' . $telefone . '?text=' . rawurlencode($mensagem) : null;
        @endphp
        @if($whatsappLink)
            <a href="{{ $whatsappLink }}" class="btn btn-sm btn-primary" target="_blank" rel="noopener noreferrer">WhatsApp</a>
        @endif
        @if($fatura->status === 'pendente')
            <form action="{{ route('faturas.marcar-pago', $fatura->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-primary">✓ Marcar como pago</button>
            </form>
        @endif
        <form action="{{ route('faturas.destroy', $fatura->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta fatura?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">🗑️ Deletar</button>
        </form>
    </div>
</div>

<div class="fatura-detail-card">
    <div class="fatura-detail-header">
        <div>
            <div class="fatura-detail-nome">{{ $fatura->consumidor->nome }}</div>
            <div class="fatura-detail-info">
                Medidor: {{ $fatura->consumidor->numero_medidor }} · Período: {{ str_pad($fatura->mes, 2, '0', STR_PAD_LEFT) }}/{{ $fatura->ano }}
            </div>
        </div>
        <div style="text-align: right;">
            <div class="fatura-detail-status">
                <span class="badge {{ $fatura->status === 'pago' ? 'badge-green' : 'badge-amber' }}">
                    {{ ucfirst($fatura->status) }}
                </span>
            </div>
            <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 0.5rem;">
                @if($fatura->data_pagamento)
                    Pago em {{ $fatura->data_pagamento->format('d/m/Y') }}
                @elseif($fatura->data_vencimento)
                    Vencimento: {{ $fatura->data_vencimento->format('d/m/Y') }}
                @endif
            </div>
        </div>
    </div>

    <div style="border-top: 1px solid var(--color-border); margin: 1.5rem 0; padding-top: 1.5rem;">
        <h3 style="font-size: 13px; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; color: var(--color-text-secondary);">Detalhes do consumo</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div>
                <div class="detail-label">Leitura anterior</div>
                <div class="detail-value">{{ number_format($fatura->leitura_anterior, 3, ',', '.') }} m³</div>
            </div>
            <div>
                <div class="detail-label">Leitura atual</div>
                <div class="detail-value">{{ number_format($fatura->leitura_atual, 3, ',', '.') }} m³</div>
            </div>
            <div>
                <div class="detail-label">Consumo em m³</div>
                <div class="detail-value">{{ number_format($fatura->consumo_m3, 3, ',', '.') }} m³</div>
            </div>
            <div>
                <div class="detail-label">Consumo em litros</div>
                <div class="detail-value">{{ number_format($fatura->consumo_litros, 0, ',', '.') }} L</div>
            </div>
        </div>
    </div>

    <div style="border-top: 1px solid var(--color-border); margin: 1.5rem 0; padding-top: 1.5rem;">
        <h3 style="font-size: 13px; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; color: var(--color-text-secondary);">Cálculo da tarifa</h3>
        
        <div class="tarifa-calculation">
            <div class="tarifa-line">
                <span>Taxa fixa (base):</span>
                <strong>R$ {{ number_format($fatura->taxa_fixa, 2, ',', '.') }}</strong>
            </div>
            
            @if($fatura->taxa_excedente > 0)
                <div class="tarifa-line">
                    <span>Taxa de excedente (por 1.000 L):</span>
                    <strong>R$ {{ number_format($fatura->taxa_excedente, 2, ',', '.') }}</strong>
                </div>
            @endif

            <div class="tarifa-line" style="border-top: 2px solid var(--color-border); padding-top: 1rem; margin-top: 1rem; font-weight: 700; font-size: 18px;">
                <span>Valor total:</span>
                <strong style="color: #27AE60; font-size: 24px;">R$ {{ number_format($fatura->total, 2, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    @if($fatura->leitura)
        <div style="border-top: 1px solid var(--color-border); margin: 1.5rem 0; padding-top: 1.5rem;">
            <h3 style="font-size: 13px; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; color: var(--color-text-secondary);">Leitura associada</h3>
            <a href="{{ route('leituras.show', $fatura->leitura->id) }}" class="btn btn-sm btn-ghost">Ver leitura</a>
        </div>
    @endif

    <div style="border-top: 1px solid var(--color-border); margin: 1.5rem 0; padding-top: 1.5rem;">
        <h3 style="font-size: 13px; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; color: var(--color-text-secondary);">Informações adicionais</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div>
                <div class="detail-label">Consumidor</div>
                <div class="detail-value">
                    <a href="{{ route('consumidores.show', $fatura->consumidor->id) }}" style="color: #0A3D62; text-decoration: none;">
                        {{ $fatura->consumidor->nome }}
                    </a>
                </div>
            </div>
            <div>
                <div class="detail-label">Data de criação</div>
                <div class="detail-value">{{ $fatura->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div>
                <div class="detail-label">Endereço</div>
                <div class="detail-value">{{ $fatura->consumidor->endereco }}</div>
            </div>
            <div>
                <div class="detail-label">Telefone</div>
                <div class="detail-value">{{ $fatura->consumidor->telefone }}</div>
            </div>
        </div>
    </div>
</div>

<style>
    .fatura-detail-card {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 0.5rem;
        padding: 2rem;
    }

    .fatura-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .fatura-detail-nome {
        font-size: 20px;
        font-weight: 700;
    }

    .fatura-detail-info {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-top: 0.5rem;
    }

    .fatura-detail-status {
        font-size: 14px;
    }

    .detail-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--color-text-primary);
    }

    .tarifa-calculation {
        background: rgba(0, 0, 0, 0.02);
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--color-border);
    }

    .tarifa-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        font-size: 14px;
    }
</style>
@endsection
