@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Leituras registradas</h2>
    <a href="{{ route('leituras.create') }}" class="btn btn-sm btn-primary">➕ Registrar leitura</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Consumidor</th>
                <th>Período</th>
                <th>Consumo</th>
                <th>Data do registro</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leituras as $leitura)
                <tr>
                    <td>
                        <a href="{{ route('consumidores.show', $leitura->consumidor->id) }}" style="color: #0A3D62; text-decoration: none;">
                            {{ $leitura->consumidor->nome }}
                        </a>
                    </td>
                    <td>{{ str_pad($leitura->mes, 2, '0', STR_PAD_LEFT) }}/{{ $leitura->ano }}</td>
                    <td>{{ number_format($leitura->consumo_m3, 3, ',', '.') }} m³ ({{ number_format($leitura->consumo_litros, 0, ',', '.') }} L)</td>
                    <td>{{ $leitura->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('leituras.show', $leitura->id) }}" class="btn btn-sm btn-ghost">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 1.5rem;">
                        Nenhuma leitura registrada. <a href="{{ route('leituras.create') }}" style="color: #0A3D62; text-decoration: underline;">Registre uma nova leitura</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $leituras->links() }}
@endsection
