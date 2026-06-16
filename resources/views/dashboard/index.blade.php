@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Dashboard — {{ now()->format('F Y') }}</h2>
</div>

<div class="stat-grid">
    <div class="stat">
        <div class="stat-label">Consumidores ativos</div>
        <div class="stat-val">{{ $consumidoresAtivos ?? 42 }}</div>
        <div class="stat-sub">total cadastrado</div>
    </div>
    <div class="stat">
        <div class="stat-label">Leituras registradas</div>
        <div class="stat-val">{{ $leiturasCadastradas ?? 38 }}</div>
        <div class="stat-sub">{{ $leiturasPendentes ?? 4 }} pendentes</div>
    </div>
    <div class="stat">
        <div class="stat-label">A receber</div>
        <div class="stat-val">R$ {{ number_format($aReceber ?? 1240, 2, ',', '.') }}</div>
        <div class="stat-sub">{{ $faturasPendentes ?? 31 }} faturas pendentes</div>
    </div>
    <div class="stat">
        <div class="stat-label">Já pago</div>
        <div class="stat-val">R$ {{ number_format($jaPago ?? 175, 2, ',', '.') }}</div>
        <div class="stat-sub">{{ $faturaspagas ?? 7 }} faturas pagas</div>
    </div>
</div>

<div class="section-title">Últimas leituras registradas</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Consumidor</th>
                <th>Medidor</th>
                <th>Consumo</th>
                <th>Valor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimasLeituras ?? [] as $leitura)
                <tr>
                    <td>{{ $leitura['consumidor'] ?? 'N/A' }}</td>
                    <td>{{ $leitura['medidor'] ?? 'N/A' }}</td>
                    <td>{{ $leitura['consumo'] ?? 'N/A' }}</td>
                    <td>R$ {{ number_format($leitura['valor'] ?? 0, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $leitura['status'] === 'pago' ? 'badge-green' : 'badge-amber' }}">
                            {{ $leitura['status'] ?? 'pendente' }}
                        </span>
                    </td>
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

@if(($leiturasPendentes ?? 0) > 0)
    <div class="alert-box alert-info">
        ℹ️ {{ $leiturasPendentes ?? 4 }} consumidores ainda sem leitura registrada neste mês.
    </div>
@endif
@endsection
