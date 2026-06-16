@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Detalhes da leitura</h2>
    <form action="{{ route('leituras.destroy', $leitura->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta leitura?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">🗑️ Deletar</button>
    </form>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
    <div class="info-card">
        <div class="info-label">Consumidor</div>
        <div class="info-value">
            <a href="{{ route('consumidores.show', $leitura->consumidor->id) }}" style="color: #0A3D62; text-decoration: none;">
                {{ $leitura->consumidor->nome }}
            </a>
        </div>
    </div>
    <div class="info-card">
        <div class="info-label">Período</div>
        <div class="info-value">{{ str_pad($leitura->mes, 2, '0', STR_PAD_LEFT) }}/{{ $leitura->ano }}</div>
    </div>
    <div class="info-card">
        <div class="info-label">Leitura anterior</div>
        <div class="info-value">{{ number_format($leitura->leitura_anterior, 3, ',', '.') }} m³</div>
    </div>
    <div class="info-card">
        <div class="info-label">Leitura atual</div>
        <div class="info-value">{{ number_format($leitura->leitura_atual, 3, ',', '.') }} m³</div>
    </div>
    <div class="info-card">
        <div class="info-label">Consumo</div>
        <div class="info-value">{{ number_format($leitura->consumo_m3, 3, ',', '.') }} m³ ({{ number_format($leitura->consumo_litros, 0, ',', '.') }} L)</div>
    </div>
    <div class="info-card">
        <div class="info-label">Data do registro</div>
        <div class="info-value">{{ $leitura->created_at->format('d/m/Y H:i') }}</div>
    </div>
</div>

@if($leitura->fatura)
    <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 1rem;">Fatura gerada</h3>
    <div class="fatura-card">
        <div class="fatura-header">
            <div>
                <div class="fatura-nome">{{ $leitura->fatura->consumidor->nome }}</div>
                <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 4px;">
                    Medidor {{ $leitura->fatura->consumidor->numero_medidor }} · {{ str_pad($leitura->fatura->mes, 2, '0', STR_PAD_LEFT) }}/{{ $leitura->fatura->ano }}
                </div>
            </div>
            <div style="text-align: right;">
                <div class="fatura-val">R$ {{ number_format($leitura->fatura->total, 2, ',', '.') }}</div>
                <span class="badge {{ $leitura->fatura->status === 'pago' ? 'badge-green' : 'badge-amber' }}">
                    {{ ucfirst($leitura->fatura->status) }}
                </span>
            </div>
        </div>

        <div class="fatura-grid">
            <div>
                <div class="fatura-item-label">Taxa fixa</div>
                <div class="fatura-item-val">R$ {{ number_format($leitura->fatura->taxa_fixa, 2, ',', '.') }}</div>
            </div>
            <div>
                <div class="fatura-item-label">Excedente</div>
                <div class="fatura-item-val">R$ {{ number_format($leitura->fatura->taxa_excedente, 2, ',', '.') }}</div>
            </div>
            <div>
                <div class="fatura-item-label">Total</div>
                <div class="fatura-item-val">R$ {{ number_format($leitura->fatura->total, 2, ',', '.') }}</div>
            </div>
        </div>

        <div style="margin-top: 1rem;">
            <a href="{{ route('faturas.show', $leitura->fatura->id) }}" class="btn btn-sm btn-primary">Ver fatura completa</a>
        </div>
    </div>
@endif

<style>
    .info-card {
        background: var(--color-bg-secondary);
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--color-border);
    }

    .info-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 16px;
        color: var(--color-text-primary);
        font-weight: 500;
    }

    .fatura-card {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .fatura-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--color-border);
    }

    .fatura-nome {
        font-weight: 600;
        font-size: 14px;
    }

    .fatura-val {
        font-size: 20px;
        font-weight: 700;
        color: #27AE60;
    }

    .fatura-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .fatura-item-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-bottom: 0.25rem;
    }

    .fatura-item-val {
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection
