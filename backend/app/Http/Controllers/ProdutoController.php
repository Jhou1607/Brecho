<?php

namespace App\Http\Controllers;

use App\Models\Imagem;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the products (for catalog).
     * Optionally handles a 'search' query parameter.
     * Endpoint: GET /api/produtos
     */
    public function index(Request $request)
    {
        $query = Produto::query();
        $produtos = $query->with('imagens')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $produtos
        ], 200);
    }

    /**
     * Display the specified product.
     * Endpoint: GET /api/produtos/{id}
     */
    public function show(string $id)
    {
        $produto = Produto::with('imagens')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $produto
        ], 200);
    }

    /**
     * Search for products based on a search term.
     * Endpoint: GET /api/produtos/search?term=...
     */
    public function search(Request $request)
    {
        $request->validate([
            'term' => ['required', 'string', 'max:255'],
        ]);

        $term = $request->term;

        $produtos = Produto::where('nome_produto', 'LIKE', "%{$term}%")
            ->orWhere('marca', 'LIKE', "%{$term}%")
            ->orWhere('categoria', 'LIKE', "%{$term}%")
            ->with('imagens')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $produtos
        ], 200);
    }

    /**
     * Upload an image for a specific product.
     * Endpoint: POST /api/produtos/{produtoId}/imagens
     */
    public function uploadImagem(Request $request, $produtoId)
    {
        $request->validate([
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_principal' => 'boolean' // Valida o campo is_principal, se enviado
        ]);

        $produto = Produto::findOrFail($produtoId);
        $path = $request->file('imagem')->store('produtos', 'public');

        $imagem = new Imagem([
            'url' => $path, // Usa 'url' em vez de 'path' para corresponder à tabela imagens
            'is_principal' => $request->input('is_principal', false) // Define como false se não enviado
        ]);
        $produto->imagens()->save($imagem);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $imagem->id,
                'url' => $path,
                'is_principal' => $imagem->is_principal
            ]
        ], 201);
    }
}
