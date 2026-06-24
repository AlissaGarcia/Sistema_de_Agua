@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <div>
        <h2 style="font-size: 16px; font-weight: 500;">Painel Leiturista</h2>
        <p style="color: #6B7280; margin: 0.5rem 0 0;">Acesse somente as leituras e registre novas leituras de água.</p>
    </div>
    <a href="{{ route('leituras.create') }}" class="btn btn-primary">➕ Registrar leitura</a>
</div>

<div class="stat-grid">
    <div class="stat">
        <div class="stat-label">Leituras registradas</div>
        <div class="stat-val">{{ $leituras->total() }}</div>
        <div class="stat-sub">total de leituras</div>
    </div>
    <div class="stat">
        <div class="stat-label">Páginas</div>
        <div class="stat-val">{{ $leituras->lastPage() }}</div>
        <div class="stat-sub">navegação rápida</div>
    </div>
</div>

<div class="section-title">Leituras recentes</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Consumidor</th>
                <th>Medidor</th>
                <th>Consumo</th>
                <th>Data</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($leituras as $leitura)
                <tr>
                    <td>{{ $leitura->consumidor->nome }}</td>
                    <td>{{ $leitura->consumidor->numero_medidor }}</td>
                    <td>{{ number_format($leitura->consumo_litros, 0, ',', '.') }} L</td>
                    <td>{{ $leitura->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $leitura->fatura?->status === 'pago' ? 'badge-green' : 'badge-amber' }}">
                            {{ $leitura->fatura?->status ?? 'sem fatura' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('leituras.show', $leitura->id) }}" class="btn btn-sm btn-ghost">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 1.5rem;">
                        Nenhuma leitura encontrada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1rem;">
    {{ $leituras->links() }}
</div>
@endsection
