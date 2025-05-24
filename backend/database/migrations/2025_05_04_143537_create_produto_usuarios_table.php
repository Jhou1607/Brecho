<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produto_usuarios', function (Blueprint $table) { // Nome da tabela em português
            $table->id(); // Cria 'id' como SERIAL PRIMARY KEY
            $table->string('nome_produto', 55);
            $table->string('marca', 55)->nullable();
            $table->string('modelo', 55)->nullable();
            $table->string('estacao')->nullable(); // Armazenar ENUMs como string
            $table->string('categoria')->nullable(); // Armazenar ENUMs como string
            $table->string('cor')->nullable(); // Armazenar ENUMs como string
            $table->string('estado_conservacao')->nullable(); // Estado de conservação
            $table->integer('numeracao')->nullable(); // Tamanho/Numeração

            // Chave estrangeira para o usuário dono do produtos próprio
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produto_usuarios'); // Nome da tabela em português
    }
};
