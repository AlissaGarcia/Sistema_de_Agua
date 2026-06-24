<?php

namespace App\Http\Controllers;

use App\Models\Leitura;

class LeituristaController extends Controller
{
    /**
     * Mostrar painel do leiturista com leituras recentes.
     */
    public function index()
    {
        $leituras = Leitura::with('consumidor')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('leiturista.index', [
            'title' => 'Painel Leiturista',
            'leituras' => $leituras,
        ]);
    }
}
