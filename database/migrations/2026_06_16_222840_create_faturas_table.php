<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumidor_id')->constrained('consumidors')->onDelete('cascade');
            $table->foreignId('leitura_id')->nullable()->constrained('leituras')->onDelete('set null');
            $table->string('mes', 2);
            $table->string('ano', 4);
            $table->decimal('leitura_anterior', 8, 3);
            $table->decimal('leitura_atual', 8, 3);
            $table->decimal('consumo_m3', 8, 3);
            $table->integer('consumo_litros');
            $table->decimal('taxa_fixa', 10, 2);
            $table->decimal('taxa_excedente', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pendente', 'pago', 'vencido'])->default('pendente');
            $table->date('data_vencimento')->nullable();
            $table->date('data_pagamento')->nullable();
            $table->timestamps();
            $table->unique(['consumidor_id', 'mes', 'ano']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturas');
    }
};
