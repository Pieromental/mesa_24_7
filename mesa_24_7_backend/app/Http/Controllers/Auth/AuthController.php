<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Utils\Response;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
         
            $loginUserData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
            $user = User::where('email',$loginUserData['email'])->first();
            if(!$user || !Hash::check($loginUserData['password'],$user->password)){
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
            return Response::error(
                code: $e->getCode(),
                message: $e->getMessage(),
                functionName: __FUNCTION__
            );
        } 
    }
}