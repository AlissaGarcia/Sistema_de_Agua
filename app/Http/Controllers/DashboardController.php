<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard com estatísticas
     */
    public function index()
    {
        // Dados mockados - substituir com dados reais do banco
        $data = [
            'title' => 'Dashboard',
            'consumidoresAtivos' => 42,
            'leiturasCadastradas' => 38,
            'leiturasPendentes' => 4,
            'aReceber' => 1240.00,
            'faturasPendentes' => 31,
            'jaPago' => 175.00,
            'faturaspagas' => 7,
            'ultimasLeituras' => [
                [
                    'consumidor' => 'Maria das Graças',
                    'medidor' => '#007',
                    'consumo' => '200 L',
                    'valor' => 25.00,
                    'status' => 'pendente'
                ],
                [
                    'consumidor' => 'João Pereira',
                    'medidor' => '#012',
                    'consumo' => '14.500 L',
                    'valor' => 34.00,
                    'status' => 'pendente'
                ],
                [
                    'consumidor' => 'Ana Souza',
                    'medidor' => '#003',
                    'consumo' => '9.000 L',
                    'valor' => 25.00,
                    'status' => 'pago'
                ],
                [
                    'consumidor' => 'Raimundo Feitosa',
                    'medidor' => '#021',
                    'consumo' => '21.000 L',
                    'valor' => 47.00,
                    'status' => 'pendente'
                ],
            ]
        ];

        return view('dashboard.index', $data);
    }
}
