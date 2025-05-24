<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo; // Para relação N:1
use Illuminate\Database\Eloquent\Relations\MorphTo; // Para relação polimórfica N:1

class Armario extends Model // Nome da classe em português
{
    use HasFactory;

    // Informa ao Eloquent qual o nome da tabela no banco de dados
    protected $table = 'armarios';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'usuario_id', // Chave estrangeira para o usuário dono do armário
        'item_id',    // Coluna polimórfica (ID do item)
        'item_type',  // Coluna polimórfica (Tipo do item - Ex: 'App\Models\Produto', 'App\Models\ProdutoUsuario')
        // Adicionar outros campos do armario se existirem na migration
        // 'ordem',
    ];

    // --- Relacionamentos ---

    /**
     * Get the user that owns the armario item.
     */
    public function usuario(): BelongsTo // Nome da relação em português
    {
        // Relação muitos-para-um com Usuario
        // 'usuario_id' é a chave estrangeira na tabela 'armarios'
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Get the parent item (Produto or ProdutoUsuario) that the armario item belongs to. (Polimórfico)
     */
    public function item(): MorphTo // Nome da relação em inglês (nome da função morphTo)
    {
        // Relação polimórfica inversa
        // 'item' corresponde ao nome usado no método morphs() na migration 'armarios'
        // Eloquent mapeará item_type para o Model correto (Produto ou ProdutoUsuario)
        return $this->morphTo();
    }
}
