<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'role' => 'required|in:admin,dinas,user',
            'dinas' => 'nullable|string',
        ]);

        $creds = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (Auth::attempt($creds, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            $selected = $validated['role'] ?? null;
            $map = ['admin' => 'super_admin', 'dinas' => 'admin_dinas', 'user' => 'user'];
            $expectedRole = $selected ? ($map[$selected] ?? null) : null;
            // Jangan blokir login jika role pilihan tidak sesuai; abaikan dan lanjutkan berdasarkan role akun

            // Require dinas selection for Admin Dinas and User; validate ownership
            if (in_array($user->role, ['admin_dinas', 'user'])) {
                $dinasCode = $validated['dinas'] ?? null;
                if (! $dinasCode) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->withErrors([
                        'dinas' => 'Silakan pilih dinas untuk melanjutkan.',
                    ])->withInput();
                }
                $selectedDinas = Dinas::where('kode_dinas', $dinasCode)->first();
                if (! $selectedDinas || ! $user->dinas || $user->dinas_id !== $selectedDinas->id) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->withErrors([
                        'dinas' => 'Dinas yang dipilih tidak sesuai dengan akun Anda.',
                    ])->withInput();
                }
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
