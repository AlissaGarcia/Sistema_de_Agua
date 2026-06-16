@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
    <h2 style="font-size: 16px; font-weight: 500;">Registrar leitura</h2>
</div>

<form action="{{ route('leituras.store') }}" method="POST" style="max-width: 600px;">
    @csrf
    
    <div class="form-group">
        <label class="form-label">Consumidor</label>
        <select 
            id="consumidor-select"
            name="consumidor_id" 
            class="form-input @error('consumidor_id') is-invalid @enderror" 
            required
        >
            <option value="">Selecione um consumidor</option>
            @foreach($consumidores as $consumidor)
                <option value="{{ $consumidor->id }}" data-leitura="{{ $consumidor->ultimaLeitura()?->leitura_atual ?? $consumidor->leitura_inicial }}" {{ old('consumidor_id') == $consumidor->id ? 'selected' : '' }}>
                    {{ $consumidor->nome }} — Medidor {{ $consumidor->numero_medidor }}
                </option>
            @endforeach
        </select>
        @error('consumidor_id')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Mês de referência</label>
            <select name="mes" class="form-input @error('mes') is-invalid @enderror" required>
                <option value="">Selecione o mês</option>
                <option value="01" {{ old('mes') == '01' ? 'selected' : '' }}>Janeiro</option>
                <option value="02" {{ old('mes') == '02' ? 'selected' : '' }}>Fevereiro</option>
                <option value="03" {{ old('mes') == '03' ? 'selected' : '' }}>Março</option>
                <option value="04" {{ old('mes') == '04' ? 'selected' : '' }}>Abril</option>
                <option value="05" {{ old('mes') == '05' ? 'selected' : '' }}>Maio</option>
                <option value="06" {{ old('mes') == '06' ? 'selected' : '' }} {{ old('mes', now()->format('m')) == '06' ? 'selected' : '' }}>Junho</option>
                <option value="07" {{ old('mes') == '07' ? 'selected' : '' }}>Julho</option>
                <option value="08" {{ old('mes') == '08' ? 'selected' : '' }}>Agosto</option>
                <option value="09" {{ old('mes') == '09' ? 'selected' : '' }}>Setembro</option>
                <option value="10" {{ old('mes') == '10' ? 'selected' : '' }}>Outubro</option>
                <option value="11" {{ old('mes') == '11' ? 'selected' : '' }}>Novembro</option>
                <option value="12" {{ old('mes') == '12' ? 'selected' : '' }}>Dezembro</option>
            </select>
            @error('mes')
                <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Ano</label>
            <input 
                type="number" 
                name="ano" 
                class="form-input @error('ano') is-invalid @enderror" 
                value="{{ old('ano', now()->year) }}" 
                required
            >
            @error('ano')
                <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Leitura anterior (m³)</label>
        <input 
            type="number" 
            id="leitura-anterior"
            name="leitura_anterior" 
            class="form-input @error('leitura_anterior') is-invalid @enderror" 
            value="{{ old('leitura_anterior') }}"
            step="0.001" 
            required
        >
        @error('leitura_anterior')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Leitura atual (m³)</label>
        <input 
            type="number" 
            id="leitura-atual"
            name="leitura_atual" 
            class="form-input @error('leitura_atual') is-invalid @enderror" 
            value="{{ old('leitura_atual') }}"
            step="0.001" 
            required
        >
        @error('leitura_atual')
            <span style="color: #C0392B; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="alert-box alert-info">
        ℹ️
        <div id="consumo-resultado">
            Digite as leituras anterior e atual para calcular o consumo e valor a pagar.
        </div>
    </div>

    <div class="btn-row">
        <a href="{{ route('consumidores.index') }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Registrar e gerar fatura</button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const consumidorSelect = document.getElementById('consumidor-select');
        const leituraAnterior = document.getElementById('leitura-anterior');
        const leituraAtual = document.getElementById('leitura-atual');
        
        // Preencher leitura anterior quando consumidor é selecionado
        if (consumidorSelect) {
            consumidorSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (selected && selected.getAttribute('data-leitura')) {
                    leituraAnterior.value = selected.getAttribute('data-leitura');
                    calcularConsumo();
                }
            });
            
            // Disparar change ao carregar se houver seleção
            if (consumidorSelect.value) {
                consumidorSelect.dispatchEvent(new Event('change'));
            }
        }
        
        function calcularConsumo() {
            const anterior = parseFloat(leituraAnterior.value || 0);
            const atual = parseFloat(leituraAtual.value || 0);
            
            if (anterior && atual && atual > anterior) {
                const consumoM3 = atual - anterior;
                const consumoL = consumoM3 * 1000;
                
                const taxaFixa = {{ $configuracao['taxa_fixa'] ?? 25 }};
                const limiteConsumo = {{ $configuracao['limite_consumo'] ?? 10000 }};
                const valorExcedente = {{ $configuracao['valor_excedente'] ?? 2 }};
                
                let total = taxaFixa;
                let excedente = 0;
                
                if (consumoL > limiteConsumo) {
                    const unidadesExcedentes = (consumoL - limiteConsumo) / 1000;
                    excedente = unidadesExcedentes * valorExcedente;
                    total = taxaFixa + excedente;
                }
                
                const resultadoEl = document.getElementById('consumo-resultado');
                resultadoEl.innerHTML = `
                    <strong>Consumo: ${consumoM3.toFixed(3)} m³ (${consumoL.toLocaleString('pt-BR')} litros)</strong><br>
                    ${consumoL > limiteConsumo ? 
                        `Acima do limite de ${(limiteConsumo/1000).toFixed(1)} m³ → R$ ${taxaFixa.toFixed(2)} (taxa fixa) + R$ ${excedente.toFixed(2)} (excedente) = <strong>R$ ${total.toFixed(2)}</strong>` 
                        : 
                        `Dentro do limite de ${(limiteConsumo/1000).toFixed(1)} m³ → <strong>R$ ${total.toFixed(2)}</strong> (taxa fixa)`
                    }
                `;
            } else if (anterior && atual && atual <= anterior) {
                const resultadoEl = document.getElementById('consumo-resultado');
                resultadoEl.innerHTML = '<span style="color: #C0392B;">⚠️ A leitura atual deve ser maior que a leitura anterior</span>';
            }
        }
        
        if (leituraAnterior) leituraAnterior.addEventListener('change', calcularConsumo);
        if (leituraAnterior) leituraAnterior.addEventListener('input', calcularConsumo);
        if (leituraAtual) leituraAtual.addEventListener('change', calcularConsumo);
        if (leituraAtual) leituraAtual.addEventListener('input', calcularConsumo);
    });
</script>
@endpush
@endsection
