<?php

namespace App\Providers;

use App\Models\Consumidor;
use App\Models\Fatura;
use App\Models\Leitura;
use App\Models\User;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(AuditObserver::class);
        Consumidor::observe(AuditObserver::class);
        Leitura::observe(AuditObserver::class);
        Fatura::observe(AuditObserver::class);
    }
}
