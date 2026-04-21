<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use function Symfony\Component\Clock\now;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6",
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'A fiók sikeresen létrehozva.'
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            $user = $request->user();

            $ipHash = hash_hmac(
                'sha256',
                request()->ip(),
                config('app.key')
            );

            UserLogin::create([
                'user_id' => $user->id,
                'ip_hash' => $ipHash
            ]);

            return response()->json(["message" => "Üdvözöljük!"]);
        }

        return response()->json(["message" => "Az e-mail cím és jelszó kombináció nem megfelelő."], 401);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
