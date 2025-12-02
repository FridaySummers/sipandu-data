<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Dinas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        $dinas = Dinas::all(); // Ambil semua dinas untuk dropdown
        return view('login', compact('dinas'));
    }

    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,admin_dinas,user',
        ]);

        // Validasi dinas_id untuk admin_dinas dan user
        if (in_array($request->role, ['admin_dinas', 'user'])) {
            $request->validate([
                'dinas_id' => 'required|exists:dinas,id',
            ]);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Validasi credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Validasi role match
        if ($user->role !== $request->role) {
            return back()->withErrors([
                'role' => 'Role tidak sesuai dengan akun.',
            ]);
        }

        // Validasi dinas_id untuk admin_dinas dan user
        if (in_array($user->role, ['admin_dinas', 'user'])) {
            if ($user->dinas_id != $request->dinas_id) {
                return back()->withErrors([
                    'dinas_id' => 'Anda tidak terdaftar di dinas yang dipilih.',
                ]);
            }
        }

        // Login user
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}