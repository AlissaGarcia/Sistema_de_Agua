<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;
use App\Services\TarifaService;

class ConfiguracaoController extends Controller
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
        $configuracao = Configuracao::firstOrCreate([], [
            'taxa_fixa' => 25.00,
            'limite_consumo' => 10000,
            'valor_excedente' => 2.00,
        ]);

        return view('configuracao.index', [
            'title' => 'Configuração de tarifas',
            'configuracao' => $configuracao->toArray()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('configuracao.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('configuracao.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('configuracao.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('configuracao.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id = null)
    {
        $validated = $request->validate([
            'taxa_fixa' => 'required|numeric|min:0',
            'valor_excedente' => 'required|numeric|min:0',
        ], [
            'taxa_fixa.required' => 'A taxa fixa é obrigatória.',
            'valor_excedente.required' => 'O valor do excedente é obrigatório.',
        ]);

        // Validar configuração com TarifaService
        $erros = $this->tarifaService->validarConfiguracao($validated);
        if (!empty($erros)) {
            return redirect()->back()
                ->withErrors($erros)
                ->withInput();
        }

        // Atualizar ou criar configuração
        $configuracao = Configuracao::first();
        if ($configuracao) {
            $configuracao->update($validated);
        } else {
            $configuracao = Configuracao::create($validated + ['limite_consumo' => 10000]);
        }

        return redirect()->route('configuracao.index')
            ->with('success', 'Configuração atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('configuracao.index');
    }
}

