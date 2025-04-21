<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Utils\Response;

/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Operaciones relacionadas a la autenticación"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Autenticación"},
     *     summary="Iniciar sesión de usuario",
     *     description="Permite al usuario autenticarse y obtener un token.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", example="admin_mesa247l@dev.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Bienvenido, Piero"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="usuario", type="object"),
     *                 @OA\Property(property="token", type="string", example="hsy27a87a...etc")
     *             )
     *         )
     *     ),
     * )
     */
    public function login(Request $request)
    {
        try {

            $loginUserData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
            $user = User::where('email', $loginUserData['email'])->first();
            if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
                return Response::response(code: 401, title: 'Usuario No Encontrado', message: 'Correo y/o Contraseña Inválidos');
            }
            $fullToken = $user->createToken('auth_token')->plainTextToken;
            $token = explode('|', $fullToken)[1] ?? $fullToken;
            return Response::response(
                code: 200,
                title: 'Inicio de sesión exitoso',
                message: 'Bienvenido, ' . $user->name,
                data: [
                    'usuario' => $user,
                    'token' => $token
                ]
            );
        } catch (GeneralException $e) {
            return Response::response(
                code: $e->getCode(),
                message: $e->getMessage(),
                functionName: __FUNCTION__
            );
        }
    }
}
