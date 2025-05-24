<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome_usuario',
        'email',
        'password',
        'sexo',
        'data_nascimento',
        'foto_url',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'data_nascimento' => 'date',
    ];

    /**
     * Get the products from brecho the user has favorited (Desejados).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function desejados(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'desejados', 'usuario_id', 'produto_id');
    }

    /**
     * Get the products owned by the user (Produtos Próprios).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produtosProprios(): HasMany
    {
        return $this->hasMany(ProdutoUsuario::class, 'usuario_id');
    }

    /**
     * Get the armario items created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function armarios(): HasMany
    {
        return $this->hasMany(Armario::class, 'usuario_id');
    }
}
