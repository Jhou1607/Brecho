<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario; // Importe o Model Usuario (seu Model em português)

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria um usuário de teste
        Usuario::create([
            'nome_usuario' => 'Teste Usuário',
            'email' => 'teste@example.com',
            'password' => Hash::make('password'), // Senha 'password' hashada
            'sexo' => 'outro', // Opcional
            'data_nascimento' => '1990-01-01', // Opcional
            'email_verified_at' => now(), // Opcional
        ]);

        // Se você escolheu a Opção B (renomear/editar UserFactory para UsuarioFactory)
        // e quer criar mais usuários com a factory, descomente a linha abaixo:
        // \App\Models\Usuario::factory()->count(10)->create();
    }
}
