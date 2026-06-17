@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">{{ $consumidor->nome }}</h2>
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('consumidores.edit', $consumidor->id) }}" class="btn btn-sm btn-primary">✏️ Editar</a>
        <form action="{{ route('consumidores.destroy', $consumidor->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este consumidor?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">🗑️ Deletar</button>
        </form>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
    <div class="info-card">
        <div class="info-label">Número do medidor</div>
        <div class="info-value">{{ $consumidor->numero_medidor }}</div>
    </div>
    <div class="info-card">
        <div class="info-label">Telefone</div>
        <div class="info-value">{{ $consumidor->telefone }}</div>
    </div>
    <div class="info-card" style="grid-column: 1 / -1;">
        <div class="info-label">Endereço</div>
        <div class="info-value">{{ $consumidor->endereco }}</div>
    </div>
</div>

<h3 style="font-size: 14px; font-weight: 600; margin-bottom: 1rem;">Leituras registradas</h3>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Período</th>
                <th>Leitura anterior</th>
                <th>Leitura atual</th>
                <th>Consumo</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consumidor->leituras->sortByDesc('created_at') as $leitura)
                <tr>
                    <td>{{ str_pad($leitura->mes, 2, '0', STR_PAD_LEFT) }}/{{ $leitura->ano }}</td>
                    <td>{{ number_format($leitura->leitura_anterior, 3, ',', '.') }} m³</td>
                    <td>{{ number_format($leitura->leitura_atual, 3, ',', '.') }} m³</td>
                    <td>{{ number_format($leitura->consumo_m3, 3, ',', '.') }} m³ ({{ number_format($leitura->consumo_litros, 0, ',', '.') }} L)</td>
                    <td>{{ $leitura->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 1.5rem;">
                        Nenhuma leitura registrada ainda.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3 style="font-size: 14px; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem;">Faturas</h3>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Período</th>
                <th>Consumo</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Data vencimento</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consumidor->faturas->sortByDesc('created_at') as $fatura)
                <tr>
                    <td>{{ str_pad($fatura->mes, 2, '0', STR_PAD_LEFT) }}/{{ $fatura->ano }}</td>
                    <td>{{ number_format($fatura->consumo_m3, 3, ',', '.') }} m³ ({{ number_format($fatura->consumo_litros, 0, ',', '.') }} L)</td>
                    <td>R$ {{ number_format($fatura->total, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $fatura->status === 'pago' ? 'badge-green' : 'badge-amber' }}">
                            {{ ucfirst($fatura->status) }}
                        </span>
                    </td>
                    <td>{{ $fatura->data_vencimento ? $fatura->data_vencimento->format('d/m/Y') : '—' }}</td>
                    <td>
                        <a href="{{ route('faturas.show', $fatura->id) }}" class="btn btn-sm btn-ghost">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 1.5rem;">
                        Nenhuma fatura gerada ainda.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

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
</style>
@endsection
