<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desejados', function (Blueprint $table) { // Nome da tabela em português
            // Chaves estrangeiras para os usuários e produtos
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');

            // Define a chave primária composta
            $table->primary(['usuario_id', 'produto_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desejados'); // Nome da tabela em português
    }
};
