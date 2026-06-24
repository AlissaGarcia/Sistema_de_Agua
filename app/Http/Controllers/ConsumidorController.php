<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsumidorRequest;
use App\Http\Requests\UpdateConsumidorRequest;
use Illuminate\Validation\Rule;
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
    public function store(StoreConsumidorRequest $request)
    {
        $validated = $request->validatedData();

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
    public function update(UpdateConsumidorRequest $request, string $id)
    {
        $consumidor = Consumidor::findOrFail($id);

        $validated = $request->validatedData();

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

