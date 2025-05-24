<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) { // Nome da tabela em português
            $table->id(); // Cria 'id' como SERIAL PRIMARY KEY
            $table->string('nome_produto', 55);
            $table->double('preco'); // Usando double para precisão
            $table->string('marca', 55)->nullable();
            $table->string('modelo', 55)->nullable();
            // Armazenando ENUMs como string
            $table->string('estado_conservacao')->nullable();
            $table->string('estacao')->nullable();
            $table->string('categoria')->nullable();
            $table->string('cor')->nullable();
            $table->integer('numeracao')->nullable(); // Tamanho/Numeração

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos'); // Nome da tabela em português
    }
};
