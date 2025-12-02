<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dinas;

class AuthController extends Controller
{
    public function showLogin()
    {
        $dinasList = Dinas::orderBy('nama_dinas')->get();
        return view('login', compact('dinasList'));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'nullable|string',
        ]);

        $creds = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (Auth::attempt($creds, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            $selected = $validated['role'] ?? null;
            $map = [
                'admin' => 'super_admin',
                'dinas' => 'admin_dinas',
                'user' => 'user',
            ];
            $expectedRole = $selected ? ($map[$selected] ?? null) : null;

            if ($expectedRole && $user->role !== $expectedRole) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'role' => 'Role yang dipilih tidak sesuai dengan akun.',
                ]);
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
