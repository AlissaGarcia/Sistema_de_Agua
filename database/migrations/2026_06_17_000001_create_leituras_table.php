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
        Schema::create('leituras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumidor_id')->constrained('consumidores')->cascadeOnDelete();
            $table->unsignedInteger('mes_referencia')->nullable();
            $table->unsignedInteger('ano_referencia')->nullable();
            $table->string('mes', 2)->nullable();
            $table->string('ano', 4)->nullable();
            $table->decimal('leitura_anterior', 10, 3);
            $table->decimal('leitura_atual', 10, 3);
            $table->decimal('consumo_m3', 10, 3);
            $table->integer('consumo_litros')->nullable();
            $table->timestamps();

            $table->unique(['consumidor_id', 'mes', 'ano']);
            $table->unique(['consumidor_id', 'mes_referencia', 'ano_referencia'], 'leituras_consumidor_mes_ano_ref_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leituras');
    }
};