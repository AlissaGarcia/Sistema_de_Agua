@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Configuração de tarifas</h2>
</div>

<form action="{{ route('configuracao.update') }}" method="POST" style="max-width: 600px;">
    @csrf
    @method('PATCH')
    
    <div class="alert-box alert-warn">
        ⚠️ Alterar esses valores afeta o cálculo de todas as faturas geradas a partir de agora.
    </div>

    <div class="form-group">
        <label class="form-label">Taxa fixa mensal (R$)</label>
        <input 
            type="number" 
            name="taxa_fixa" 
            class="form-input @error('taxa_fixa') is-invalid @enderror" 
            value="{{ old('taxa_fixa', $configuracao['taxa_fixa'] ?? 25.00) }}"
            step="0.01" 
            required
        >
        <small style="color: var(--color-text-secondary); margin-top: 4px; display: block;">
            Cobrada para consumos até o limite abaixo.
        </small>
        @error('taxa_fixa')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Valor do excedente por 1.000 litros (R$)</label>
        <input 
            type="number" 
            name="valor_excedente" 
            class="form-input @error('valor_excedente') is-invalid @enderror" 
            value="{{ old('valor_excedente', $configuracao['valor_excedente'] ?? 2.00) }}"
            step="0.01" 
            required
        >
        @error('valor_excedente')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="alert-box alert-info">
        ℹ️ Esses valores são usados automaticamente nas novas leituras e faturas.
    </div>

    <div class="btn-row">
        <a href="{{ route('dashboard') }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar configuração</button>
    </div>
</form>
@endsection
