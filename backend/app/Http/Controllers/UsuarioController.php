<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Importar Rule para validação unique
use Illuminate\Support\Facades\Log; // Para logs, se necessário

class UsuarioController extends Controller
{
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $usuario = $request->user();
        $nomeArquivo = Str::uuid() . '.' . $request->file('foto')->getClientOriginalExtension();
        $caminho = 'fotos/perfil';

        if ($usuario->foto_url) {
            // Extrair o caminho relativo do storage a partir da URL completa, se necessário
            $pathAntigo = Str::replaceFirst(Storage::url(''), '', $usuario->foto_url_original_path ?? $usuario->foto_url);
            if(Storage::disk('public')->exists($pathAntigo)) {
                Storage::disk('public')->delete($pathAntigo);
            }
        }

        $caminhoCompleto = $request->file('foto')->storeAs($caminho, $nomeArquivo, 'public');

        $usuario->update([
            'foto_url' => $caminhoCompleto, // Salvar o caminho relativo
        ]);

        // Atualizar o usuário no Auth service do frontend pode requerer que retorne o usuário completo
        $usuarioAtualizado = Usuario::find($usuario->id);
        // Formatar foto_url para a resposta
        $usuarioAtualizado->foto_url = $usuarioAtualizado->foto_url ? Storage::url($usuarioAtualizado->foto_url) : null;


        return response()->json([
            'message' => 'Foto de perfil atualizada com sucesso!',
            'foto_url' => Storage::url($caminhoCompleto), // URL completa para o frontend
            'user' => $usuarioAtualizado // Retornar o usuário atualizado
        ], 200);
    }

    /**
     * Atualizar dados do perfil do usuário autenticado.
     */
    public function updateProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $usuario = $request->user();

        $validated = $request->validate([
            'nome_usuario' => 'sometimes|required|string|max:55',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:55',
                Rule::unique('usuarios')->ignore($usuario->id), // Ignorar o próprio usuário na verificação de unicidade
            ],
            'data_nascimento' => 'sometimes|required|date',
            'sexo' => 'sometimes|required|string|in:masculino,feminino',
            'bio' => 'nullable|string|max:1000', // Bio pode ser nula ou uma string
        ]);

        // Atualizar apenas os campos que foram validados e enviados na requisição
        $usuario->fill($validated);
        $usuario->save();

        // Para retornar a foto_url formatada corretamente como no método 'me'
        $usuario->foto_url = $usuario->foto_url ? Storage::url($usuario->foto_url) : null;

        return response()->json([
            'message' => 'Perfil atualizado com sucesso!',
            'user' => $usuario
        ], 200);
    }
}
