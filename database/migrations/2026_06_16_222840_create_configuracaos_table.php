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
        Schema::create('configuracaos', function (Blueprint $table) {
            $table->id();
            $table->decimal('taxa_fixa', 10, 2)->default(25.00);
            $table->integer('limite_consumo')->default(10000);
            $table->decimal('valor_excedente', 10, 2)->default(2.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracaos');
    }
};
