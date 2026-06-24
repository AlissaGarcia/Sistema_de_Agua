<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeituraRequest;
use App\Http\Requests\UpdateLeituraRequest;
use App\Models\Consumidor;
use App\Models\Leitura;
use App\Models\Fatura;
use App\Models\Configuracao;
use App\Services\TarifaService;

class LeituraController extends Controller
{
    protected TarifaService $tarifaService;

    public function __construct()
    {
        $this->tarifaService = new TarifaService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leituras = Leitura::with('consumidor')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('leituras.index', [
            'title' => 'Leituras',
            'leituras' => $leituras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $consumidores = Consumidor::orderBy('nome')->get();
        $configuracao = $this->tarifaService->getConfiguracao();

        return view('leituras.create', [
            'title' => 'Registrar leitura',
            'consumidores' => $consumidores,
            'configuracao' => $configuracao
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeituraRequest $request)
    {
        $validated = $request->validatedData();

        $consumidor = Consumidor::findOrFail($validated['consumidor_id']);
        $leituraAnterior = $consumidor->ultimaLeitura()?->leitura_atual ?? 0;

        if ($validated['leitura_atual'] < $leituraAnterior) {
            return redirect()->back()
                ->withErrors(['leitura_atual' => 'A leitura atual não pode ser menor que a leitura anterior.'])
                ->withInput();
        }

        // Verificar se já existe leitura para este consumidor neste mês/ano
        $leituraExistente = Leitura::where('consumidor_id', $validated['consumidor_id'])
            ->where('mes', $validated['mes'])
            ->where('ano', $validated['ano'])
            ->exists();

        if ($leituraExistente) {
            return redirect()->back()
                ->withErrors(['mes' => 'Já existe uma leitura registrada para este consumidor neste mês/ano.'])
                ->withInput();
        }

        // Calcular consumo em m³ e litros
        $consumo_m3 = $validated['leitura_atual'] - $leituraAnterior;
        $consumo_litros = (int)($consumo_m3 * 1000);

        // Usar TarifaService para calcular tarifa
        $calculo = $this->tarifaService->calcularTarifa($consumo_litros);

        // Criar leitura
        $leitura = Leitura::create([
            'consumidor_id' => $validated['consumidor_id'],
            'mes' => $validated['mes'],
            'ano' => $validated['ano'],
            'leitura_anterior' => $leituraAnterior,
            'leitura_atual' => $validated['leitura_atual'],
            'consumo_m3' => $consumo_m3,
            'consumo_litros' => $consumo_litros,
        ]);

        // Gerar fatura
        $fatura = Fatura::create([
            'consumidor_id' => $validated['consumidor_id'],
            'leitura_id' => $leitura->id,
            'mes' => $validated['mes'],
            'ano' => $validated['ano'],
            'leitura_anterior' => $leituraAnterior,
            'leitura_atual' => $validated['leitura_atual'],
            'consumo_m3' => $consumo_m3,
            'consumo_litros' => $consumo_litros,
            'taxa_fixa' => $calculo['taxa_fixa'],
            'taxa_excedente' => $calculo['taxa_excedente'],
            'total' => $calculo['total'],
            'status' => 'pendente',
            'data_vencimento' => now()->addDays(10),
        ]);

        return redirect()->route('faturas.show', $fatura->id)
            ->with('success', 'Leitura registrada e fatura gerada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leitura = Leitura::with('consumidor', 'fatura')->findOrFail($id);

        return view('leituras.show', [
            'title' => 'Detalhes da leitura',
            'leitura' => $leitura
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leitura = Leitura::findOrFail($id);
        $consumidores = Consumidor::orderBy('nome')->get();

        return view('leituras.edit', [
            'title' => 'Editar leitura',
            'leitura' => $leitura,
            'consumidores' => $consumidores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeituraRequest $request, string $id)
    {
        $leitura = Leitura::findOrFail($id);

        $validated = $request->validatedData();

        $consumo_m3 = $validated['leitura_atual'] - $validated['leitura_anterior'];
        $consumo_litros = (int)($consumo_m3 * 1000);

        $leitura->update([
            'leitura_anterior' => $validated['leitura_anterior'],
            'leitura_atual' => $validated['leitura_atual'],
            'consumo_m3' => $consumo_m3,
            'consumo_litros' => $consumo_litros,
        ]);

        return redirect()->route('leituras.show', $leitura->id)
            ->with('success', 'Leitura atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leitura = Leitura::findOrFail($id);
        $leitura->delete();

        return redirect()->route('leituras.index')
            ->with('success', 'Leitura removida com sucesso!');
    }
}
