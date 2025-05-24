<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario; // Importe o Model Usuario
use App\Models\ProdutoUsuario; // Importe o Model ProdutoUsuario

class ProdutoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela (opcional)
        // ProdutoUsuario::query()->delete();

        // Encontra o usuário de teste criado pelo UsuarioSeeder
        $usuarioTeste = Usuario::where('email', 'teste@example.com')->first();

        if ($usuarioTeste) {
            // Cria produtos próprios associados a este usuário
            ProdutoUsuario::create([
                'nome_produto' => 'Minha Blusa Favorita',
                'marca' => 'Zara',
                'modelo' => 'Cropped',
                'estacao' => 'verão',
                'categoria' => 'blusa',
                'cor' => 'branca',
                'estado_conservacao' => 'seminovo',
                'numeracao' => 38, // Tamanho
                'usuario_id' => $usuarioTeste->id, // Associa ao usuário
            ]);

            ProdutoUsuario::create([
                'nome_produto' => 'Meu Tênis de Corrida',
                'marca' => 'Nike',
                'modelo' => 'Air Max',
                'estacao' => 'verão', // Tênis pode ser usado em qualquer estação
                'categoria' => 'calçado',
                'cor' => 'preto',
                'estado_conservacao' => 'usado',
                'numeracao' => 42, // Tamanho
                'usuario_id' => $usuarioTeste->id, // Associa ao usuário
            ]);

            // Crie mais produtos próprios se necessário
            // \App\Models\ProdutoUsuario::factory()->count(5)->create(['usuario_id' => $usuarioTeste->id]); // Se usar factories
        }
    }
}
