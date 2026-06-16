@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Faturas — {{ now()->format('F Y') }}</h2>
</div>

@forelse($faturas ?? [] as $fatura)
    <div class="fatura-card">
        <div class="fatura-header">
            <div>
                <div class="fatura-nome">{{ $fatura['consumidor'] ?? 'N/A' }}</div>
                <div style="font-size: 12px; color: var(--color-text-secondary); margin-top: 4px;">
                    Medidor {{ $fatura['medidor'] ?? 'N/A' }} · {{ $fatura['mes'] ?? 'N/A' }}/{{ $fatura['ano'] ?? now()->year }}
                </div>
            </div>
            <div style="text-align: right;">
                <div class="fatura-val">R$ {{ number_format($fatura['total'] ?? 0, 2, ',', '.') }}</div>
                <span class="badge {{ $fatura['status'] === 'pago' ? 'badge-green' : 'badge-amber' }}">
                    {{ $fatura['status'] ?? 'pendente' }}
                </span>
            </div>
        </div>

        <div class="fatura-grid">
            <div>
                <div class="fatura-item-label">Leitura anterior</div>
                <div class="fatura-item-val">{{ $fatura['leitura_anterior'] ?? '0' }} m³</div>
            </div>
            <div>
                <div class="fatura-item-label">Leitura atual</div>
                <div class="fatura-item-val">{{ $fatura['leitura_atual'] ?? '0' }} m³</div>
            </div>
            <div>
                <div class="fatura-item-label">Consumo</div>
                <div class="fatura-item-val">{{ $fatura['consumo'] ?? '0' }} L</div>
            </div>
            <div>
                <div class="fatura-item-label">Taxa fixa</div>
                <div class="fatura-item-val">R$ {{ number_format($fatura['taxa_fixa'] ?? 0, 2, ',', '.') }}</div>
            </div>
            <div>
                <div class="fatura-item-label">Excedente</div>
                <div class="fatura-item-val">
                    @if($fatura['excedente'] ?? 0)
                        R$ {{ number_format($fatura['excedente'], 2, ',', '.') }}
                    @else
                        —
                    @endif
                </div>
            </div>
            <div>
                <div class="fatura-item-label">Total</div>
                <div class="fatura-item-val">R$ {{ number_format($fatura['total'] ?? 0, 2, ',', '.') }}</div>
            </div>
        </div>

        @if($fatura['status'] === 'pendente')
            <form action="{{ route('faturas.marcar-pago', ['id' => $fatura['id'] ?? 1]) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-primary">Marcar como pago</button>
            </form>
        @endif
        
        <button class="wa-btn" onclick="enviarWhatsApp('{{ $fatura['telefone'] ?? '' }}', '{{ $fatura['consumidor'] ?? '' }}', '{{ number_format($fatura['total'] ?? 0, 2, ',', '.') }}')">
            💬 Enviar WhatsApp
        </button>
    </div>
@empty
    <div style="text-align: center; padding: 3rem 1rem;">
        <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">Nenhuma fatura gerada ainda.</p>
        <a href="{{ route('leituras.create') }}" class="btn btn-primary">Registrar leitura para gerar fatura</a>
    </div>
@endforelse

@push('scripts')
<script>
    function enviarWhatsApp(telefone, consumidor, valor) {
        if (!telefone) {
            alert('Telefone não disponível');
            return;
        }
        const mensagem = `Olá ${consumidor}, sua fatura no valor de R$ ${valor} está disponível no sistema de controle de água.`;
        const url = `https://wa.me/${telefone.replace(/\D/g, '')}?text=${encodeURIComponent(mensagem)}`;
        window.open(url, '_blank');
    }
</script>
@endpush
@endsection
