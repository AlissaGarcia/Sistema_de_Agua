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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('consumidores', function (Blueprint $table) {
            if (! Schema::hasColumn('consumidores', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('leituras', function (Blueprint $table) {
            if (! Schema::hasColumn('leituras', 'deleted_at')) {
                $table->softDeletes();
            }

            $table->dropForeign(['consumidor_id']);
            $table->foreign('consumidor_id')
                ->references('id')
                ->on('consumidores')
                ->restrictOnDelete();
        });

        Schema::table('faturas', function (Blueprint $table) {
            if (! Schema::hasColumn('faturas', 'deleted_at')) {
                $table->softDeletes();
            }

            $table->dropForeign(['leitura_id']);
            $table->dropForeign(['consumidor_id']);

            $table->foreign('leitura_id')
                ->nullable()
                ->references('id')
                ->on('leituras')
                ->restrictOnDelete();

            $table->foreign('consumidor_id')
                ->references('id')
                ->on('consumidores')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropForeign(['leitura_id']);
            $table->dropForeign(['consumidor_id']);

            $table->foreign('leitura_id')
                ->nullable()
                ->references('id')
                ->on('leituras')
                ->nullOnDelete();

            $table->foreign('consumidor_id')
                ->references('id')
                ->on('consumidores')
                ->cascadeOnDelete();

            $table->dropSoftDeletes();
        });

        Schema::table('leituras', function (Blueprint $table) {
            $table->dropForeign(['consumidor_id']);
            $table->foreign('consumidor_id')
                ->references('id')
                ->on('consumidores')
                ->cascadeOnDelete();

            $table->dropSoftDeletes();
        });

        Schema::table('consumidores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
