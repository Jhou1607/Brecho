<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        // Depuração: Logar os dados recebidos
        \Log::info('Dados recebidos no register:', $request->all());

        $validated = $request->validate([
            'nome_usuario' => 'required|string|max:55',
            'email' => 'required|string|email|max:55|unique:usuarios',
            'password' => 'required|string|min:6|confirmed', // Adicionada validação confirmed
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string|in:masculino,feminino',
        ]);

        $user = \App\Models\Usuario::create([
            'nome_usuario' => $validated['nome_usuario'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'data_nascimento' => $validated['data_nascimento'],
            'sexo' => $validated['sexo'],
        ]);

        return response()->json(['message' => 'Usuário registrado com sucesso', 'user' => $user], 201);
    }

    /**
     * Log in a user and create a Sanctum token.
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ], [
                'email.required' => 'O campo email é obrigatório.',
                'email.email' => 'Por favor, insira um email válido.',
                'password.required' => 'O campo senha é obrigatório.',
            ]);

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciais inválidas.'
                ], 401);
            }

            $usuario = Auth::user();
            $token = $usuario->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login bem-sucedido.',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao tentar fazer login.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log out the authenticated user (revoke token).
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout bem-sucedido.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao tentar fazer logout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retornar dados do usuário autenticado.
     */
    public function me(Request $request)
    {
        try {
            $usuario = $request->user();
            if (!$usuario) {
                return response()->json(['message' => 'Usuário não autenticado'], 401);
            }

            // Verificar se foto_url existe e formatar o caminho
            $usuario->foto_url = isset($usuario->foto_url) && $usuario->foto_url
                ? Storage::url($usuario->foto_url)
                : null;

            // Retornar os dados esperados pelo frontend
            return response()->json([
                'id' => $usuario->id,
                'nome_usuario' => $usuario->nome_usuario,
                'email' => $usuario->email,
                'sexo' => $usuario->sexo,
                'data_nascimento' => $usuario->data_nascimento,
                'foto_url' => $usuario->foto_url,
                'bio' => $usuario->bio ?? null,
                'created_at' => $usuario->created_at,
                'updated_at' => $usuario->updated_at,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Erro no método me:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Erro ao carregar dados do usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
