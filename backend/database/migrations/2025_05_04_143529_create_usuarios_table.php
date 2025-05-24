<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) { // Nome da tabela em português
            $table->id(); // Cria 'id' como SERIAL PRIMARY KEY (bigserial em PostgreSQL)
            $table->string('nome_usuario', 55);
            $table->string('email', 55)->unique(); // Email único
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Coluna para senha hash (VARCHAR 255)
            $table->rememberToken();

            // Novos Campos solicitados
            $table->string('sexo')->nullable();
            $table->date('data_nascimento')->nullable();

            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios'); // Nome da tabela em português
    }
};
