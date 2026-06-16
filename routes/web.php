<?php

use Illuminate\Support\Facades\Route;

// Login e autenticação
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Implementar lógica de login
    return redirect('/dashboard');
})->name('login.post');

Route::post('/logout', function () {
    // Implementar lógica de logout
    return redirect('/login');
})->name('logout');

// Grupo de rotas autenticadas
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.index', [
            'title' => 'Dashboard',
            'consumidoresAtivos' => 42,
            'leiturasCadastradas' => 38,
            'leiturasPendentes' => 4,
            'aReceber' => 1240,
            'faturasPendentes' => 31,
            'jaPago' => 175,
            'faturaspagas' => 7,
            'ultimasLeituras' => [
                ['consumidor' => 'Maria das Graças', 'medidor' => '#007', 'consumo' => '200 L', 'valor' => 25.00, 'status' => 'pendente'],
                ['consumidor' => 'João Pereira', 'medidor' => '#012', 'consumo' => '14.500 L', 'valor' => 34.00, 'status' => 'pendente'],
                ['consumidor' => 'Ana Souza', 'medidor' => '#003', 'consumo' => '9.000 L', 'valor' => 25.00, 'status' => 'pago'],
                ['consumidor' => 'Raimundo Feitosa', 'medidor' => '#021', 'consumo' => '21.000 L', 'valor' => 47.00, 'status' => 'pendente'],
            ]
        ]);
    })->name('dashboard');

    // Consumidores
    Route::get('/consumidores', function () {
        return view('consumidores.index', [
            'title' => 'Consumidores',
            'consumidores' => [
                ['id' => 1, 'nome' => 'Ana Souza', 'medidor' => '#003', 'endereco' => 'Rua das Flores, 12', 'telefone' => '(88) 99001-2345', 'status' => 'ativo'],
                ['id' => 2, 'nome' => 'Maria das Graças', 'medidor' => '#007', 'endereco' => 'Rua do Açude, 45', 'telefone' => '(88) 98765-4321', 'status' => 'ativo'],
                ['id' => 3, 'nome' => 'João Pereira', 'medidor' => '#012', 'endereco' => 'Travessa Nova, 8', 'telefone' => '(88) 99123-0001', 'status' => 'ativo'],
                ['id' => 4, 'nome' => 'Raimundo Feitosa', 'medidor' => '#021', 'endereco' => 'Rua do Sítio, 3', 'telefone' => '(88) 99456-7890', 'status' => 'ativo'],
                ['id' => 5, 'nome' => 'Francisca Lima', 'medidor' => '#028', 'endereco' => 'Beco da Lagoa, 2', 'telefone' => '(88) 99321-6543', 'status' => 'inativo'],
            ]
        ]);
    })->name('consumidores.index');

    Route::get('/consumidores/novo', function () {
        return view('consumidores.create', ['title' => 'Novo consumidor']);
    })->name('consumidores.create');

    Route::post('/consumidores', function () {
        // Implementar armazenamento de consumidor
        return redirect()->route('consumidores.index')->with('success', 'Consumidor cadastrado com sucesso!');
    })->name('consumidores.store');

    // Leituras
    Route::get('/leituras/nova', function () {
        return view('leituras.create', [
            'title' => 'Registrar leitura',
            'consumidores' => [
                ['id' => 1, 'nome' => 'Ana Souza', 'medidor' => '#003'],
                ['id' => 2, 'nome' => 'Maria das Graças', 'medidor' => '#007'],
                ['id' => 3, 'nome' => 'João Pereira', 'medidor' => '#012'],
                ['id' => 4, 'nome' => 'Raimundo Feitosa', 'medidor' => '#021'],
            ],
            'configuracao' => [
                'taxa_fixa' => 25.00,
                'limite_consumo' => 10000,
                'valor_excedente' => 2.00
            ]
        ]);
    })->name('leituras.create');

    Route::post('/leituras', function () {
        // Implementar armazenamento de leitura
        return redirect()->route('dashboard')->with('success', 'Leitura registrada e fatura gerada com sucesso!');
    })->name('leituras.store');

    // Faturas
    Route::get('/faturas', function () {
        return view('faturas.index', [
            'title' => 'Faturas',
            'faturas' => [
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
            ]
        ]);
    })->name('faturas.index');

    Route::patch('/faturas/{id}/marcar-pago', function ($id) {
        // Implementar marcação de fatura como paga
        return redirect()->route('faturas.index')->with('success', 'Fatura marcada como paga!');
    })->name('faturas.marcar-pago');

    // Configuração
    Route::get('/configuracao', function () {
        return view('configuracao.index', [
            'title' => 'Configuração de tarifas',
            'configuracao' => [
                'taxa_fixa' => 25.00,
                'limite_consumo' => 10000,
                'valor_excedente' => 2.00
            ]
        ]);
    })->name('configuracao.index');

    Route::patch('/configuracao', function () {
        // Implementar atualização de configuração
        return redirect()->route('configuracao.index')->with('success', 'Configuração atualizada com sucesso!');
    })->name('configuracao.update');
});

// Redirecionar raiz para login
Route::get('/', function () {
    return redirect('/login');
});
