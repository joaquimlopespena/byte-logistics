<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SanctumTokenController extends Controller
{
    /**
     * Emite um token Bearer (API) após validar email e password.
     *
     * @see https://laravel.com/docs/13.x/sanctum#mobile-application-authentication
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('As credenciais fornecidas estão incorretas.')],
            ]);
        }

        $token = $user->createToken($validated['device_name']);

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
