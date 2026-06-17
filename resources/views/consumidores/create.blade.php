@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Novo consumidor</h2>
</div>

<form action="{{ route('consumidores.store') }}" method="POST" style="max-width: 600px;">
    @csrf
    
    <div class="form-group">
        <label class="form-label">Nome completo</label>
        <input 
            type="text" 
            name="nome" 
            class="form-input @error('nome') is-invalid @enderror" 
            value="{{ old('nome') }}"
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
            value="{{ old('endereco') }}"
            required
        >
        @error('endereco')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Número do medidor</label>
            <input 
                type="text" 
                name="numero_medidor" 
                class="form-input @error('numero_medidor') is-invalid @enderror" 
                value="{{ old('numero_medidor') }}"
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
                value="{{ old('telefone') }}"
                required
            >
            @error('telefone')
                <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="btn-row">
        <a href="{{ route('consumidores.index') }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar consumidor</button>
    </div>
</form>
@endsection
