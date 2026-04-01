<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Buyers register and enter by their name only.
        // We reuse their account if they use the exact same name, or create a new one.
        $user = User::firstOrCreate(
            ['name' => $validated['name'], 'role' => 'buyer'],
            [
                'email' => \Illuminate\Support\Str::slug($validated['name']) . '_' . uniqid() . '@buyer.local',
                'password' => Hash::make(\Illuminate\Support\Str::random(16)),
            ]
        );

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user()->role);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    private function redirectByRole(string $role)
    {
        if ($role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return redirect()->route('buyer.dashboard');
    }
}
