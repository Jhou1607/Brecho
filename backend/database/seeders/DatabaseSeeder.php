<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Importe os Seeders que você quer chamar
use Database\Seeders\UsuarioSeeder;
use Database\Seeders\ProdutoSeeder; // Para produtos do brechó
use Database\Seeders\ProdutoUsuarioSeeder; // Para produtos próprios
use Database\Seeders\ImagemSeeder; // Imagens para ambos
use Database\Seeders\ArmarioSeeder; // Itens do armário


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chame outros seeders na ordem correta de dependência
        $this->call([
            UsuarioSeeder::class,       // Primeiro usuários
            ProdutoSeeder::class,       // Depois produtos do brechó
            ProdutoUsuarioSeeder::class, // Depois produtos próprios
            ImagemSeeder::class,        // Depois imagens (referenciam produtos e produtos próprios)
            ArmarioSeeder::class,       // Por fim armários (referenciam itens)
        ]);
    }
}
