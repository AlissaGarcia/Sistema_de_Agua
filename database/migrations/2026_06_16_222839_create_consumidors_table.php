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
        Schema::create('consumidors', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('endereco');
            $table->string('numero_medidor')->unique();
            $table->string('telefone');
            $table->decimal('leitura_inicial', 8, 3)->default(0);
            $table->enum('status', ['ativo', 'inativo', 'suspenso'])->default('ativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumidors');
    }
};
