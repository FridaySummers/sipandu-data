<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureDinasAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin bebas akses semua
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Admin Dinas dan User Dinas boleh akses 1 dinas sesuai dinas_id
        if (in_array($user->role, ['admin_dinas','user']) && $user->dinas) {
            $segment = $request->segment(2); // setelah prefix 'dinas'
            $kode = $user->dinas->kode_dinas;

            // Pemetaan slug dinas -> segment route
            $map = [
                'dpmptsp' => 'dpmptsp',
                'dinas-perdagangan' => 'perdagangan',
                'dinas-perindustrian' => 'perindustrian',
                'dinas-koperasi-dan-ukm' => 'koperasi',
                'dinas-pertanian-tanaman-pangan' => 'tanaman-pangan',
                'dinas-perkebunan-dan-peternakan' => 'perkebunan',
                'dinas-perikanan' => 'perikanan',
                'dinas-ketahanan-pangan' => 'ketapang',
                'dinas-pariwisata' => 'pariwisata',
                'dinas-lingkungan-hidup' => 'dlh',
                'badan-pendapatan-daerah' => 'bapenda',
            ];

            $allowedSegment = $map[$kode] ?? null;

            if ($allowedSegment && $segment === $allowedSegment) {
                return $next($request);
            }

            return redirect()->route('dashboard');
        }

        // Role lain tidak punya akses ke halaman dinas
        return redirect()->route('dashboard');
    }
}
