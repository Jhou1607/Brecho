<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage; // ADICIONE ESTA LINHA

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome_produto',
        'preco',
        'marca',
        'modelo',
        'estado_conservacao',
        'estacao',
        'categoria',
        'cor',
        'numeracao',
    ];

    public function imagens(): MorphMany
    {
        return $this->morphMany(Imagem::class, 'imageable');
    }

    public function desejadoPor(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'desejados', 'produto_id', 'usuario_id');
    }

    public function armarios(): MorphMany
    {
        return $this->morphMany(Armario::class, 'item');
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['imagens'] = $this->imagens->map(function ($imagem) {
            return [
                'id' => $imagem->id,
                'url' => $imagem->url ? Storage::url($imagem->url) : null, // MODIFICADO AQUI
                'is_principal' => $imagem->is_principal,
            ];
        })->toArray();
        return $array;
    }
}
