<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validData= $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        // Il devra y avoir un champ password_confirmation dans la requête
        ]);
        // Hash the password
        $validData['password'] = bcrypt($validData['password']);
        // Save the user
        $user = User::create($validData);
        // Create token
        $token = $user->createToken($request->email);
        return response()->json(['user' => $user, 'token' => $token->plainTextToken], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
        'email' => 'required|string|exists:users,email',
        'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Identifiants invalides'],
        401);
        }
        $token = $user->createToken($request->email);
        return response()->json(['user' => $user, 'token' => $token->plainTextToken], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }
}
