@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Consumidores</h2>
    <a href="{{ route('consumidores.create') }}" class="btn btn-primary btn-sm">
        ➕ Novo consumidor
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Medidor</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consumidores ?? [] as $consumidor)
                <tr>
                    <td>{{ $consumidor['nome'] ?? 'N/A' }}</td>
                    <td>{{ $consumidor['medidor'] ?? 'N/A' }}</td>
                    <td>{{ $consumidor['endereco'] ?? 'N/A' }}</td>
                    <td>{{ $consumidor['telefone'] ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $consumidor['status'] === 'ativo' ? 'badge-green' : 'badge-gray' }}">
                            {{ $consumidor['status'] ?? 'ativo' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-ghost">Editar</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 1.5rem;">
                        Nenhum consumidor cadastrado. <a href="{{ route('consumidores.create') }}" style="color: #0A3D62; text-decoration: underline;">Cadastre um novo</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
