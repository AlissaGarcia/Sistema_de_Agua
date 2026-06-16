<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsumidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dados mockados - substituir com Consumidor::all()
        $consumidores = [
            [
                'id' => 1,
                'nome' => 'Ana Souza',
                'medidor' => '#003',
                'endereco' => 'Rua das Flores, 12',
                'telefone' => '(88) 99001-2345',
                'status' => 'ativo'
            ],
            [
                'id' => 2,
                'nome' => 'Maria das Graças',
                'medidor' => '#007',
                'endereco' => 'Rua do Açude, 45',
                'telefone' => '(88) 98765-4321',
                'status' => 'ativo'
            ],
            [
                'id' => 3,
                'nome' => 'João Pereira',
                'medidor' => '#012',
                'endereco' => 'Travessa Nova, 8',
                'telefone' => '(88) 99123-0001',
                'status' => 'ativo'
            ],
            [
                'id' => 4,
                'nome' => 'Raimundo Feitosa',
                'medidor' => '#021',
                'endereco' => 'Rua do Sítio, 3',
                'telefone' => '(88) 99456-7890',
                'status' => 'ativo'
            ],
            [
                'id' => 5,
                'nome' => 'Francisca Lima',
                'medidor' => '#028',
                'endereco' => 'Beco da Lagoa, 2',
                'telefone' => '(88) 99321-6543',
                'status' => 'inativo'
            ],
        ];

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
            'numero_medidor' => 'required|string|unique:consumidores',
            'telefone' => 'required|string|max:20',
            'leitura_inicial' => 'required|numeric|min:0',
        ], [
            'numero_medidor.unique' => 'Este número de medidor já está cadastrado.'
        ]);

        // TODO: Criar consumidor no banco de dados
        // Consumidor::create($validated);

        return redirect()->route('consumidores.index')
            ->with('success', 'Consumidor cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Buscar consumidor no banco
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Implementar edição
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
        ]);

        // TODO: Atualizar consumidor no banco

        return redirect()->route('consumidores.index')
            ->with('success', 'Consumidor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Deletar consumidor do banco

        return redirect()->route('consumidores.index')
            ->with('success', 'Consumidor removido com sucesso!');
    }
}
