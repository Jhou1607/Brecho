<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario; // Importe o Model Usuario
use App\Models\Produto; // Importe o Model Produto
use App\Models\ProdutoUsuario; // Importe o Model ProdutoUsuario
use App\Models\Armario; // Importe o Model Armario

class ArmarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela (opcional)
        // Armario::query()->delete();

        // Encontra o usuário de teste
        $usuarioTeste = Usuario::where('email', 'teste@example.com')->first();

        // Pega alguns produtos do brechó e produtos próprios
        $produtoBrecho = Produto::first(); // Pega o primeiro produtos do brechó semeado
        $produtoProprio = ProdutoUsuario::where('usuario_id', $usuarioTeste->id)->first(); // Pega um produtos próprio do usuário de teste

        if ($usuarioTeste) {
            // Cria registros no armário associados a este usuário
            if ($produtoBrecho) {
                Armario::create([
                    'usuario_id' => $usuarioTeste->id, // Associa ao usuário
                    'item_id' => $produtoBrecho->id, // ID do item
                    'item_type' => \App\Models\Produto::class, // Tipo do Model do item
                    // Adicionar outros campos do armario se existirem na migration (ex: ordem)
                    // 'ordem' => 1,
                ]);
            }

            if ($produtoProprio) {
                Armario::create([
                    'usuario_id' => $usuarioTeste->id, // Associa ao usuário
                    'item_id' => $produtoProprio->id, // ID do item
                    'item_type' => \App\Models\ProdutoUsuario::class, // Tipo do Model do item
                    // 'ordem' => 2,
                ]);
            }

            // Exemplo: adicionar o mesmo produtos do brechó novamente (outro item no armário)
            if ($produtoBrecho) {
                Armario::create([
                    'usuario_id' => $usuarioTeste->id,
                    'item_id' => $produtoBrecho->id,
                    'item_type' => \App\Models\Produto::class,
                    // 'ordem' => 3,
                ]);
            }


            // Você pode criar mais registros de armário
            // Armario::factory()->count(5)->create(['usuario_id' => $usuarioTeste->id]); // Se usar factories
        }
    }
}
