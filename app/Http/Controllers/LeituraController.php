<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeituraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Listar leituras do banco
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $consumidores = [
            ['id' => 1, 'nome' => 'Ana Souza', 'medidor' => '#003'],
            ['id' => 2, 'nome' => 'Maria das Graças', 'medidor' => '#007'],
            ['id' => 3, 'nome' => 'João Pereira', 'medidor' => '#012'],
            ['id' => 4, 'nome' => 'Raimundo Feitosa', 'medidor' => '#021'],
        ];

        $configuracao = [
            'taxa_fixa' => 25.00,
            'limite_consumo' => 10000,
            'valor_excedente' => 2.00
        ];

        return view('leituras.create', [
            'title' => 'Registrar leitura',
            'consumidores' => $consumidores,
            'configuracao' => $configuracao
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumidor_id' => 'required|integer|exists:consumidores,id',
            'mes' => 'required|digits:2',
            'ano' => 'required|digits:4|integer',
            'leitura_anterior' => 'required|numeric|min:0',
            'leitura_atual' => 'required|numeric|min:0|gt:leitura_anterior',
        ], [
            'leitura_atual.gt' => 'A leitura atual deve ser maior que a leitura anterior.'
        ]);

        // Calcular consumo e valor
        $consumo_m3 = $validated['leitura_atual'] - $validated['leitura_anterior'];
        $consumo_l = $consumo_m3 * 1000;

        // TODO: Buscar configuração do banco
        $taxa_fixa = 25.00;
        $limite_consumo = 10000;
        $valor_excedente = 2.00;

        $total = $taxa_fixa;
        $excedente = 0;

        if ($consumo_l > $limite_consumo) {
            $excedente = (($consumo_l - $limite_consumo) / 1000) * $valor_excedente;
            $total = $taxa_fixa + $excedente;
        }

        // TODO: Criar leitura no banco
        // Leitura::create([...]);

        // TODO: Gerar fatura
        // Fatura::create([...]);

        return redirect()->route('dashboard')
            ->with('success', 'Leitura registrada e fatura gerada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Buscar leitura no banco
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Implementar edição de leitura
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Atualizar leitura no banco
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Deletar leitura do banco

        return redirect()->back()
            ->with('success', 'Leitura removida com sucesso!');
    }
}
