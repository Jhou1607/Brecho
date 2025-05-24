<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FavoritosController;

Route::get('/', function () {
    return response()->json(['message' => 'API Brecho LoopLook is working!', 'version' => '1.0']);
});


// Rotas de Autenticação (Públicas)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas de Produtos (Públicas - geralmente não precisam de login para ver)
Route::get('/produtos', [ProdutoController::class, 'index']); // Listagem geral/catálogo
Route::get('/produtos/{id}', [ProdutoController::class, 'show']); // Detalhes de um produtos específico
Route::get('/produtos/search', [ProdutoController::class, 'search']); // Busca de produtos


// Rotas Protegidas (Exigem um token Sanctum válido no cabeçalho Authorization)
Route::middleware('auth:sanctum')->group(function () {


    Route::post('products/{produtoId}/imagens', [ProdutoController::class, 'uploadImagem']);
    // Rotas de Autenticação Protegidas
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rota para pegar dados do usuário logado (Perfil)
    Route::get('/me', [AuthController::class, 'me']);

    // Rotas de Favoritos (Protegidas)
    // POST /api/favorites/toggle
    Route::post('/favorites/toggle', [FavoritosController::class, 'toggle']);
    // Opcional: GET /api/desejados
    Route::get('/desejados', [FavoritosController::class, 'index']);


    Route::post('/usuario/foto', [UsuarioController::class, 'uploadFoto'])->name('usuario.uploadFoto');
    Route::put('/usuario/profile', [UsuarioController::class, 'updateProfile'])->name('usuario.updateProfile');

    // Adicionar aqui futuras rotas protegidas (ex: criar/atualizar ProdutoUsuario, gerenciar Armario, etc.)
});

// Rotas para Produtos Próprios do Usuário (Exemplo - provavelmente protegidas para ações de escrita)
// Route::middleware('auth:sanctum')->group(function () {
//      Route::post('/produtos-usuarios', [ProdutoUsuarioController::class, 'store']); // Criar produtos próprio
//      Route::put('/produtos-usuarios/{id}', [ProdutoUsuarioController::class, 'update']); // Atualizar produtos próprio
//      // etc.
// });

// Rotas para Armario (Exemplo - provavelmente protegidas)
// Route::middleware('auth:sanctum')->group(function () {
//      Route::post('/armario', [ArmarioController::class, 'store']); // Adicionar item ao armário
//      // etc.
// });
