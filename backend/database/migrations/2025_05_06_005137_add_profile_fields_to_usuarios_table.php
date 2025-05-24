<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_profile_fields_to_usuarios_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Adiciona a coluna para URL da foto de perfil após a coluna 'sexo'
            $table->string('foto_url', 2048)->nullable()->after('sexo'); // 2048 para URLs longas, nullable pois pode não ter foto
            // Adiciona a coluna para a bio após a foto_url
            $table->text('bio')->nullable()->after('foto_url'); // TEXT para bios mais longas, nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Remove as colunas se a migration for revertida
            $table->dropColumn(['foto_url', 'bio']);
        });
    }
};
