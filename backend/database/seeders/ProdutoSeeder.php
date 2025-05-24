<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produto; // Importe o Model Produto em português

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela antes de semear (opcional, útil durante o desenvolvimento)
        // Produto::truncate(); // Use truncate com cuidado, reseta PKs em alguns DBs
        // Ou apenas delete os existentes se não quer apagar tudo:
        // Produto::query()->delete();

        // Exemplo de criação de produtos manualmente
        Produto::create([
            'nome_produto' => 'Camiseta Vintage Azul',
            'preco' => 45.99,
            'marca' => 'Reserva',
            'modelo' => 'Estampada',
            'estado_conservacao' => 'seminovo', // Valores que correspondem ao seu ENUM original (agora strings)
            'estacao' => 'verão',
            'categoria' => 'camisa',
            'cor' => 'azul claro',
            'numeracao' => 40, // Tamanho M (INT)
        ]);

        Produto::create([
            'nome_produto' => 'Calça Jeans Reta Clássica',
            'preco' => 85.50,
            'marca' => 'Levi\'s',
            'modelo' => '501',
            'estado_conservacao' => 'usado',
            'estacao' => 'outono',
            'categoria' => 'calça',
            'cor' => 'azul',
            'numeracao' => 38, // Tamanho 38 (INT)
        ]);

        Produto::create([
            'nome_produto' => 'Vestido Floral Leve',
            'preco' => 65.00,
            'marca' => 'Farm',
            'modelo' => 'Curto',
            'estado_conservacao' => 'novo',
            'estacao' => 'primavera',
            'categoria' => 'vestido',
            'cor' => 'vermelho',
            'numeracao' => 36, // Tamanho P (INT)
        ]);

        // Você pode adicionar mais produtos se necessário, ou usar factories se configurou para Produto
        // \App\Models\Produto::factory()->count(10)->create();
    }
}
