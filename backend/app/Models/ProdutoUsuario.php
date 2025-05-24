<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo; // Para relação N:1
use Illuminate\Database\Eloquent\Relations\MorphMany; // Para relação polimórfica 1:N

class ProdutoUsuario extends Model // Nome da classe em português
{
    use HasFactory;

    // Informa ao Eloquent qual o nome da tabela no banco de dados
    protected $table = 'produto_usuarios';

    // Define os campos que podem ser preenchidos em massa
    protected $fillable = [
        'nome_produto',
        'marca',
        'modelo',
        'estacao',
        'categoria',
        'cor',
        'estado_conservacao', // Adicionado na migration
        'numeracao', // Adicionado na migration
        'usuario_id', // Chave estrangeira para o usuário dono
    ];

    // Se quiser mapear 'nome_produto' para 'name', 'numeracao' para 'size', etc. para compatibilidade Angular (opcional)
    // protected $appends = ['name', 'size', ...];
    // protected $hidden = ['nome_produto', 'numeracao', ...];

    // Accessor de exemplo para mapear nome_produto para name (opcional, se precisar no Angular)
    // public function getNameAttribute(): ?string { return $this->attributes['nome_produto']; }
    // Accessor de exemplo para mapear numeracao para size (string) (opcional, se precisar no Angular)
    // public function getSizeAttribute(): ?string { return (string) $this->attributes['numeracao']; }


    // --- Relacionamentos ---

    /**
     * Get the user that owns the product.
     */
    public function usuario(): BelongsTo // Nome da relação em português
    {
        // Relação muitos-para-um com Usuario
        // 'usuario_id' é a chave estrangeira na tabela 'produto_usuarios'
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Get all of the images for the produtos usuario. (Polimórfico)
     */
    public function imagens(): MorphMany // Nome da relação em português
    {
        // Relação polimórfica um-para-muitos com Imagem
        // 'imageable' corresponde ao nome usado no método morphs() na migration 'imagens'
        return $this->morphMany(Imagem::class, 'imageable');
    }

    /**
     * Get all of the armarios that contain this produtos usuario. (Polimórfico inverso)
     */
    public function armarios(): MorphMany // Nome da relação em português
    {
        // Relação polimórfica inversa com Armario (itens do tipo ProdutoUsuario)
        // 'item' corresponde ao nome usado no método morphs() na migration 'armarios'
        return $this->morphMany(Armario::class, 'item');
    }
}
