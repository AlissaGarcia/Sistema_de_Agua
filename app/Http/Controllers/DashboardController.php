<?php

namespace App\Http\Controllers;

use App\Models\Consumidor;
use App\Models\Leitura;
use App\Models\Fatura;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard com estatísticas
     */
    public function index()
    {
        // Obter estatísticas do banco de dados
        $consumidoresAtivos = Consumidor::where('status', 'ativo')->count();
        $leiturasCadastradas = Leitura::count();
        $leiturasPendentes = Leitura::whereDoesntHave('fatura')->count();
        
        $faturasPendentes = Fatura::where('status', 'pendente')->count();
        $aReceber = Fatura::where('status', 'pendente')->sum('total');
        
        $faturaspagas = Fatura::where('status', 'pago')->count();
        $jaPago = Fatura::where('status', 'pago')->sum('total');
        
        $ultimasLeituras = Leitura::with('consumidor')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($leitura) {
                return [
                    'consumidor' => $leitura->consumidor->nome,
                    'medidor' => $leitura->consumidor->numero_medidor,
                    'consumo' => number_format($leitura->consumo_litros, 0, ',', '.') . ' L',
                    'valor' => $leitura->fatura?->total ?? 0,
                    'status' => $leitura->fatura?->status ?? 'sem_fatura'
                ];
            });

        $data = [
            'title' => 'Dashboard',
            'consumidoresAtivos' => $consumidoresAtivos,
            'leiturasCadastradas' => $leiturasCadastradas,
            'leiturasPendentes' => $leiturasPendentes,
            'aReceber' => $aReceber,
            'faturasPendentes' => $faturasPendentes,
            'jaPago' => $jaPago,
            'faturaspagas' => $faturaspagas,
            'ultimasLeituras' => $ultimasLeituras
        ];

        return view('dashboard.index', $data);
    }
}

