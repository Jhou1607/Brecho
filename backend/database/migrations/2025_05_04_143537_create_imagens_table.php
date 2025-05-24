<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagens', function (Blueprint $table) { // Nome da tabela em português
            $table->id(); // Cria 'id' como SERIAL PRIMARY KEY

            // Colunas polimórficas: imageable_id (BIGINT) e imageable_type (VARCHAR)
            // Para que a imagem possa pertencer a Produto ou ProdutoUsuario
            $table->morphs('imageable');

            $table->string('url'); // URL ou caminho do arquivo da imagem
            $table->boolean('is_principal')->default(false)->nullable(); // Se precisar marcar uma principal (opcional)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagens'); // Nome da tabela em português
    }
};
