<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaturaRequest;
use App\Http\Requests\UpdateFaturaRequest;
use App\Models\Fatura;
use App\Models\Consumidor;

class FaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mesAtual = now()->format('m');
        $anoAtual = now()->format('Y');

        $faturas = Fatura::with('consumidor')
            ->where('mes', $mesAtual)
            ->where('ano', $anoAtual)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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
    public function store(StoreFaturaRequest $request)
    {
        return redirect()->route('faturas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fatura = Fatura::with('consumidor', 'leitura')->findOrFail($id);

        return view('faturas.show', [
            'title' => 'Detalhes da fatura',
            'fatura' => $fatura
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fatura = Fatura::findOrFail($id);

        return view('faturas.edit', [
            'title' => 'Editar fatura',
            'fatura' => $fatura
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaturaRequest $request, string $id)
    {
        $fatura = Fatura::findOrFail($id);

        $validated = $request->validatedData();

        $fatura->update($validated);

        return redirect()->route('faturas.show', $fatura->id)
            ->with('success', 'Fatura atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fatura = Fatura::findOrFail($id);
        $fatura->delete();

        return redirect()->route('faturas.index')
            ->with('success', 'Fatura removida com sucesso!');
    }

    /**
     * Marcar fatura como paga
     */
    public function marcarPago($id)
    {
        $fatura = Fatura::findOrFail($id);
        $fatura->marcarComoPaga();

        return redirect()->route('faturas.index')
            ->with('success', 'Fatura marcada como paga!');
    }

    /**
     * Gerar PDF da fatura
     */
    public function gerarPDF($id)
    {
        $fatura = Fatura::with('consumidor')->findOrFail($id);

        // TODO: Gerar PDF da fatura
        // return PDF::download('faturas.pdf', ['fatura' => $fatura]);
        
        return back();
    }

    /**
     * Enviar fatura por email
     */
    public function enviarEmail($id)
    {
        $fatura = Fatura::with('consumidor')->findOrFail($id);

        // TODO: Enviar fatura por email
        // Mail::send('emails.fatura', ['fatura' => $fatura], function ($message) use ($fatura) {
        //     $message->to($fatura->consumidor->email)
        //         ->subject('Fatura de água - ' . $fatura->getPeriodo());
        // });
        
        return back()->with('success', 'Fatura enviada por email com sucesso!');
    }
}

