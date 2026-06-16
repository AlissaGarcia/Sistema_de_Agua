<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dados mockados - substituir com Fatura::all()
        $faturas = [
            [
                'id' => 1,
                'consumidor' => 'João Pereira',
                'medidor' => '#012',
                'mes' => 'Junho',
                'ano' => 2026,
                'leitura_anterior' => '142.5',
                'leitura_atual' => '157.0',
                'consumo' => '14.500 L',
                'taxa_fixa' => 25.00,
                'excedente' => 9.00,
                'total' => 34.00,
                'status' => 'pendente',
                'telefone' => '5588991230001'
            ],
            [
                'id' => 2,
                'consumidor' => 'Ana Souza',
                'medidor' => '#003',
                'mes' => 'Junho',
                'ano' => 2026,
                'leitura_anterior' => '89.0',
                'leitura_atual' => '98.0',
                'consumo' => '9.000 L',
                'taxa_fixa' => 25.00,
                'excedente' => 0,
                'total' => 25.00,
                'status' => 'pago',
                'telefone' => '5588990012345'
            ]
        ];

        return view('faturas.index', [
            'title' => 'Faturas',
            'faturas' => $faturas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('leituras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('faturas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Buscar fatura no banco
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Implementar edição de fatura
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Atualizar fatura no banco
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Deletar fatura do banco

        return redirect()->route('faturas.index')
            ->with('success', 'Fatura removida com sucesso!');
    }

    /**
     * Marcar fatura como paga
     */
    public function marcarPago($id)
    {
        // TODO: Atualizar status da fatura para 'pago' no banco
        // Fatura::find($id)->update(['status' => 'pago', 'data_pagamento' => now()]);

        return redirect()->route('faturas.index')
            ->with('success', 'Fatura marcada como paga!');
    }

    /**
     * Gerar PDF da fatura
     */
    public function gerarPDF($id)
    {
        // TODO: Gerar PDF da fatura
        // return PDF::download('faturas.show', $fatura);
    }

    /**
     * Enviar fatura por email
     */
    public function enviarEmail($id)
    {
        // TODO: Enviar fatura por email
        
        return back()->with('success', 'Fatura enviada por email com sucesso!');
    }
}
