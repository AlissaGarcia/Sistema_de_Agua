@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Faturas — {{ now()->format('F Y') }}</h2>
    <a href="{{ route('leituras.create') }}" class="btn btn-sm btn-primary">➕ Registrar leitura</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-label">Pendentes</div>
        <div class="stat-value">{{ $faturas->where('status', 'pendente')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pagas este mês</div>
        <div class="stat-value">{{ $faturas->where('status', 'pago')->whereMonth('data_pagamento', now()->month)->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Valor total em aberto</div>
        <div class="stat-value">R$ {{ number_format($faturas->where('status', 'pendente')->sum('total'), 2, ',', '.') }}</div>
    </div>
</div>

@forelse($faturas as $fatura)
    <div class="fatura-card">
        <div class="fatura-header">
            <div>
                <div class="fatura-nome">
                    <a href="{{ route('consumidores.show', $fatura->consumidor->id) }}" style="color: inherit; text-decoration: none;">
                        {{ $fatura->consumidor->nome }}
                    </a>
                </div>
                <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 4px;">
                    Medidor {{ $fatura->consumidor->numero_medidor }} · {{ str_pad($fatura->mes, 2, '0', STR_PAD_LEFT) }}/{{ $fatura->ano }}
                </div>
            </div>
            <div style="text-align: right;">
                <div class="fatura-val">R$ {{ number_format($fatura->total, 2, ',', '.') }}</div>
                <span class="badge {{ $fatura->status === 'pago' ? 'badge-green' : 'badge-amber' }}">
                    {{ ucfirst($fatura->status) }}
                </span>
            </div>
        </div>

        <div class="fatura-grid">
            <div>
                <div class="fatura-item-label">Leitura anterior</div>
                <div class="fatura-item-val">{{ number_format($fatura->leitura_anterior, 3, ',', '.') }} m³</div>
            </div>
            <div>
                <div class="fatura-item-label">Leitura atual</div>
                <div class="fatura-item-val">{{ number_format($fatura->leitura_atual, 3, ',', '.') }} m³</div>
            </div>
            <div>
                <div class="fatura-item-label">Consumo</div>
                <div class="fatura-item-val">{{ number_format($fatura->consumo_m3, 3, ',', '.') }} m³</div>
            </div>
            <div>
                <div class="fatura-item-label">Taxa fixa</div>
                <div class="fatura-item-val">R$ {{ number_format($fatura->taxa_fixa, 2, ',', '.') }}</div>
            </div>
            <div>
                <div class="fatura-item-label">Excedente</div>
                <div class="fatura-item-val">
                    @if($fatura->taxa_excedente > 0)
                        R$ {{ number_format($fatura->taxa_excedente, 2, ',', '.') }}
                    @else
                        —
                    @endif
                </div>
            </div>
            <div>
                <div class="fatura-item-label">Data vencimento</div>
                <div class="fatura-item-val">{{ $fatura->data_vencimento ? $fatura->data_vencimento->format('d/m/Y') : '—' }}</div>
            </div>
        </div>

        <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
            <a href="{{ route('faturas.show', $fatura->id) }}" class="btn btn-sm btn-ghost">Ver</a>
            @if($fatura->status === 'pendente')
                <form action="{{ route('faturas.marcar-pago', $fatura->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-primary">Marcar como pago</button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div style="text-align: center; padding: 3rem 1rem;">
        <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">Nenhuma fatura gerada ainda.</p>
        <a href="{{ route('leituras.create') }}" class="btn btn-primary">Registrar leitura para gerar fatura</a>
    </div>
@endforelse

<style>
    .stat-card {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
    }

    .stat-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0A3D62;
    }

    .fatura-card {
        background: var(--color-bg-secondary);
        border: 1px solid var(--color-border);
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .fatura-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--color-border);
    }

    .fatura-nome {
        font-weight: 600;
        font-size: 16px;
    }

    .fatura-val {
        font-size: 24px;
        font-weight: 700;
        color: #27AE60;
    }

    .fatura-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
    }

    .fatura-item-label {
        font-size: 12px;
        color: var(--color-text-secondary);
        margin-bottom: 0.5rem;
    }

    .fatura-item-val {
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection
