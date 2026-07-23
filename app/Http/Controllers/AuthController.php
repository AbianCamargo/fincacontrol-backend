<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Inicia sesión y retorna el token de acceso
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        $user = Auth::user();

        // Verifica que el usuario esté activo
        if (!$user->activo) {
            Auth::logout();
            return response()->json([
                'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'rol'    => $user->rol,
            ],
        ], 200);
    }

    // Cierra sesión e invalida el token actual
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ], 200);
    }

// Retorna los datos del usuario autenticado
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'rol'   => $user->rol,
        ], 200);
    }

    // Crea el primer usuario admin en producción — ruta temporal, protegida con clave secreta
    public function setupInicial(Request $request)
    {
        $request->validate([
            'clave_secreta' => 'required|string',
            'name'          => 'required|string',
            'email'         => 'required|email',
            'password'      => 'required|string|min:6',
        ]);

        // Verifica la clave secreta antes de permitir la creación
        if ($request->clave_secreta !== 'fincacontrol-setup-2026') {
            return response()->json(['message' => 'Clave secreta incorrecta.'], 403);
        }

        // Evita crear más de un usuario con esta ruta si ya existe alguno
        if (User::count() > 0) {
            return response()->json(['message' => 'Ya existe al menos un usuario. Esta ruta está deshabilitada.'], 403);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'rol'      => 'admin',
            'activo'   => true,
        ]);

        return response()->json(['message' => 'Usuario admin creado correctamente.', 'user' => $user], 201);
    }
}
