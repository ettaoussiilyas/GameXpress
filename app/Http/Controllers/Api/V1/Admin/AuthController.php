<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\Admin\Controller;
use App\Notifications\StockLowNotification;
use Illuminate\Http\Request;
use App\Models\User; // ou Admin si vous avez un modèle spécifique
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]);
        $user->assignRole($request->role);

        $token = $user->createToken('api-token')->plainTextToken;

        // if (!$user->hasRole($request->role)) {
        //     return response()->json([
        //         'error' => "Le rôle {$request->role} n'a pas été assigné",
        //         'user_roles' => $user->roles->pluck('name'),
        //     ], 400);
        // }
        // $user->notify(new StockLowNotification($product));

        return response()->json([
    'message' => 'Utilisateur enregistré avec succès',
    'token' => $token,
    'role' => $user->roles->first()->name,
    'permissions' => $user->getAllPermissions()->pluck('name'),
], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $permissions = $user->getAllPermissions();

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            // 'role' => $user->role->name,
            'permissions' => $permissions,
        ]);
    }
    public function logout(Request $request)
    {
        // Révoquer tous les tokens de l'utilisateur connecté
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}