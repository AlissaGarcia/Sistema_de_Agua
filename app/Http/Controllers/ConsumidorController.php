<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consumidor;

class ConsumidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumidores = Consumidor::orderBy('nome')->paginate(15);

        return view('consumidores.index', [
            'title' => 'Consumidores',
            'consumidores' => $consumidores
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('consumidores.create', [
            'title' => 'Novo consumidor'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'numero_medidor' => 'required|string|unique:consumidors',
            'telefone' => 'required|string|max:20',
            'leitura_inicial' => 'required|numeric|min:0',
        ], [
            'numero_medidor.unique' => 'Este número de medidor já está cadastrado.'
        ]);

        $consumidor = Consumidor::create($validated);

        return redirect()->route('consumidores.show', $consumidor->id)
            ->with('success', 'Consumidor cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $consumidor = Consumidor::with('leituras', 'faturas')->findOrFail($id);

        return view('consumidores.show', [
            'title' => 'Detalhes do consumidor',
            'consumidor' => $consumidor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $consumidor = Consumidor::findOrFail($id);

        return view('consumidores.edit', [
            'title' => 'Editar consumidor',
            'consumidor' => $consumidor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $consumidor = Consumidor::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'status' => 'required|in:ativo,inativo,suspenso',
        ]);

        $consumidor->update($validated);

        return redirect()->route('consumidores.show', $consumidor->id)
            ->with('success', 'Consumidor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $consumidor = Consumidor::findOrFail($id);
        $consumidor->delete();

        return redirect()->route('consumidores.index')
            ->with('success', 'Consumidor removido com sucesso!');
    }
}

