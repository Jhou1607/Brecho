<?php

namespace App\Http\Controllers;

use App\Models\Produto; // Importe o Model Produto para validar a existência do produtos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para pegar o usuário autenticado
use Illuminate\Validation\ValidationException; // Para lidar com erros de validação

class FavoritosController extends Controller // Nome da classe em português (Favoritos)
{
    /**
     * Toggle a product in the authenticated user's favorites list.
     * Endpoint: POST /api/favorites/toggle
     * Requires Authentication.
     * Espera um JSON no corpo da requisição com: { "product_id": id_do_produto }
     */
    public function toggle(Request $request)
    {
        // O usuário autenticado está disponível via Auth::user() ou $request->user()
        // A middleware 'auth:sanctum' na rota garante que haja um usuário autenticado aqui
        $usuario = Auth::user(); // Pega o usuário autenticado (Model Usuario)

        try {
            // Validar os dados de entrada
            $request->validate([
                // Verifica se product_id é obrigatório, é um inteiro, e EXISTE na tabela 'produtos' na coluna 'id'
                'product_id' => ['required', 'integer', 'exists:produtos,id'],
            ],
                [
                    // Mensagens de erro personalizadas (opcional)
                    'product_id.required' => 'O ID do produtos é obrigatório.',
                    'product_id.integer' => 'O ID do produtos deve ser um número inteiro.',
                    'product_id.exists' => 'O produtos com o ID fornecido não existe.',
                ]);

            // Pega o ID do produtos da requisição validada
            $produtoId = $request->product_id;

            // Usa a relação 'desejados' no Model Usuario (que mapeia para a tabela 'desejados')
            // toggle() adiciona o ID se não existe e remove se existe na tabela pivô
            // O método toggle() retorna um array com 'attached' e 'detached' IDs
            $result = $usuario->desejados()->toggle($produtoId);

            // Determina se o produtos foi adicionado ou removido com base no resultado do toggle
            $status = '';
            if (in_array($produtoId, $result['attached'])) {
                $status = 'adicionado';
            } elseif (in_array($produtoId, $result['detached'])) {
                $status = 'removido';
            } else {
                // Caso improvável, pode indicar que o produtos já estava no estado desejado ou erro
                $status = 'inalterado';
            }


            // Opcional: Buscar o produtos para retornar alguns dados na resposta
            // $produtos = Produto::find($produtoId);


            // Retorna resposta de sucesso indicando o status da operação
            return response()->json([
                'message' => "Produto {$status} aos desejados.",
                'status' => $status, // 'adicionado', 'removido', ou 'inalterado'
                'product_id' => $produtoId,
                // 'produtos' => $produtos, // Opcional: retorna dados do produtos
                // 'toggle_result' => $result // O resultado completo do método toggle (opcional, para debug)
            ], 200); // 200 OK

        } catch (ValidationException $e) {
            // Captura erros de validação (ex: product_id faltando ou inválido)
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            // Captura outros erros gerais (ex: erro no banco de dados)
            // Log do erro no servidor para depuração
            \Log::error('Erro ao tentar atualizar desejados:', ['error' => $e->getMessage(), 'user_id' => $usuario->id ?? 'N/A']);

            return response()->json([
                'message' => 'Ocorreu um erro ao tentar atualizar os desejados.',
                'error' => $e->getMessage() // Retorne o erro apenas em ambiente de desenvolvimento
            ], 500); // 500 Internal Server Error
        }
    }

    // Opcional: Método para listar os produtos desejados pelo usuário autenticado
    // Endpoint: GET /api/desejados
    // Requires Authentication.
    // Seu Angular pode chamar este endpoint para carregar a lista de desejados do perfil
    public function index()
    {
        // O usuário autenticado está disponível via Auth::user()
        $usuario = Auth::user();

        try {
            // Carrega os produtos desejados pelo usuário
            // Eager loading as imagens relacionadas para cada produtos desejado
            $desejados = $usuario->desejados()->with('imagens')->get();

            // Retorna a lista de produtos desejados
            return response()->json($desejados, 200);

        } catch (\Exception $e) {
            // Captura erros gerais
            \Log::error('Erro ao listar desejados:', ['error' => $e->getMessage(), 'user_id' => $usuario->id ?? 'N/A']);

            return response()->json([
                'message' => 'Ocorreu um erro ao tentar listar os desejados.',
                'error' => $e->getMessage() // Retorne o erro apenas em ambiente de desenvolvimento
            ], 500); // 500 Internal Server Error
        }
    }
}
