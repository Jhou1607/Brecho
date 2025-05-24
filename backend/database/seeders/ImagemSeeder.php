<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto; // Importe o Model Produto
use App\Models\ProdutoUsuario; // Importe o Model ProdutoUsuario
use App\Models\Imagem; // Importe o Model Imagem

class ImagemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela (opcional)
        // Imagem::query()->delete();

        // --- Adicionar imagens para Produtos do Brechó ---
        $produtosBrecho = Produto::all();

        foreach ($produtosBrecho as $produto) {
            // Adiciona 2 imagens para cada produtos do brechó
            Imagem::create([
                'imageable_id' => $produto->id,
                'imageable_type' => \App\Models\Produto::class, // Indica que o pai é um Model Produto
                'url' => 'https://via.placeholder.com/300x400/FF0000/FFFFFF/?text=' . urlencode($produto->nome_produto) . '+Front',
                'is_principal' => true,
            ]);
            Imagem::create([
                'imageable_id' => $produto->id,
                'imageable_type' => \App\Models\Produto::class,
                'url' => 'https://via.placeholder.com/300x400/00FF00/FFFFFF/?text=' . urlencode($produto->nome_produto) . '+Back',
                'is_principal' => false,
            ]);
        }

        // --- Adicionar imagens para Produtos Próprios do Usuário ---
        // Pega todos os produtos próprios que foram semeados
        $produtosProprios = ProdutoUsuario::all();

        foreach ($produtosProprios as $produtoUsuario) {
            // Adiciona 1 imagem para cada produtos próprio (exemplo)
            Imagem::create([
                'imageable_id' => $produtoUsuario->id,
                'imageable_type' => \App\Models\ProdutoUsuario::class, // Indica que o pai é um Model ProdutoUsuario
                'url' => 'https://via.placeholder.com/300x400/0000FF/FFFFFF/?text=' . urlencode($produtoUsuario->nome_produto) . '+Proprio',
                'is_principal' => true, // Pode ser a principal para este item
            ]);
            // Adicione mais imagens se necessário
        }
    }
}
