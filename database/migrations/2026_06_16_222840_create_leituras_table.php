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
            $table->foreignId('consumidor_id')->constrained('consumidors')->onDelete('cascade');
            $table->string('mes', 2);
            $table->string('ano', 4);
            $table->decimal('leitura_anterior', 8, 3);
            $table->decimal('leitura_atual', 8, 3);
            $table->decimal('consumo_m3', 8, 3);
            $table->integer('consumo_litros');
            $table->timestamps();
            $table->unique(['consumidor_id', 'mes', 'ano']);
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
