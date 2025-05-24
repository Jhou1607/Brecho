<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo; // Para relação polimórfica N:1

class Imagem extends Model // Nome da classe em português
{
    use HasFactory;

    // Informa ao Eloquent qual o nome da tabela no banco de dados
    protected $table = 'imagens';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'imageable_id', // Coluna polimórfica criada por $table->morphs('imageable')
        'imageable_type', // Coluna polimórfica criada por $table->morphs('imageable')
        'url',
        'is_principal',
    ];

    // Se quiser mapear 'id' para 'id_imagem' na resposta JSON para compatibilidade Angular (opcional)
    // protected $appends = ['id_imagem'];
    // protected $hidden = ['id'];
    // public function getIdImagemAttribute(): int { return $this->attributes['id']; }


    // --- Relacionamentos ---

    /**
     * Get the parent model (Produto or ProdutoUsuario) that the image belongs to. (Polimórfico)
     */
    public function imageable(): MorphTo // Nome da relação em inglês (nome da função morphTo)
    {
        // Relação polimórfica inversa
        // 'imageable' corresponde ao nome usado no método morphs() na migration 'imagens'
        // Eloquent mapeará imageable_type para o Model correto (Produto ou ProdutoUsuario)
        return $this->morphTo();
    }
}
