<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('armarios', function (Blueprint $table) { // Nome da tabela em português
            $table->id(); // Cria 'id' como SERIAL PRIMARY KEY

            // Chave estrangeira para o usuário dono do armário/look
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');

            // Colunas polimórficas: item_id (BIGINT) e item_type (VARCHAR)
            // Para que o item no armário possa ser um Produto ou ProdutoUsuario
            $table->morphs('item');

            // Adicionar campos adicionais para o item do armário, se necessário (ex: ordem, posição no look)
            // $table->integer('ordem')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armarios'); // Nome da tabela em português
    }
};
