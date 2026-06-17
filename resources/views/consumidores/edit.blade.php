@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Editar consumidor</h2>
</div>

<form action="{{ route('consumidores.update', $consumidor->id) }}" method="POST" style="max-width: 600px;">
    @csrf
    @method('PUT')
    
    <div class="form-group">
        <label class="form-label">Nome completo</label>
        <input 
            type="text" 
            name="nome" 
            class="form-input @error('nome') is-invalid @enderror" 
            value="{{ old('nome', $consumidor->nome) }}"
            required
        >
        @error('nome')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Endereço</label>
        <input 
            type="text" 
            name="endereco" 
            class="form-input @error('endereco') is-invalid @enderror" 
            value="{{ old('endereco', $consumidor->endereco) }}"
            required
        >
        @error('endereco')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Número do medidor</label>
        <input 
            type="text" 
            name="numero_medidor" 
            class="form-input @error('numero_medidor') is-invalid @enderror"
            value="{{ old('numero_medidor', $consumidor->numero_medidor) }}"
            required
        >
        @error('numero_medidor')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Telefone</label>
        <input 
            type="tel" 
            name="telefone" 
            class="form-input @error('telefone') is-invalid @enderror" 
            value="{{ old('telefone', $consumidor->telefone) }}"
            required
        >
        @error('telefone')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="btn-row">
        <a href="{{ route('consumidores.show', $consumidor->id) }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar alterações</button>
    </div>
</form>
@endsection
