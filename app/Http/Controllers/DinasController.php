<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\DataSubmission;
use Illuminate\Http\Request;

class DinasController extends Controller
{
    public function show($id)
    {
        $dinas = Dinas::findOrFail($id);
        $user = auth()->user();
        
        // Authorization check - pastikan user hanya akses dinas mereka (kecuali super admin)
        if ($user->role !== 'super_admin' && $user->dinas_id != $dinas->id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get data submissions dengan filter yang benar
        $query = DataSubmission::where('dinas_id', $dinas->id);
        
        // User hanya lihat data mereka sendiri + data approved dari user lain
        if ($user->role === 'user') {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('status', 'approved');
            });
        }
        
        $dataSubmissions = $query->with('user')
            ->latest()
            ->get();

        // SPECIAL CASE: DPMPTSP (ID 1) pakai view khusus
        if ($dinas->id == 1) {
            return view('dinas.dpmptsp', compact('dinas', 'dataSubmissions'));
        }
            
        // Default view untuk dinas lainnya
        return view('dinas.show', compact('dinas', 'dataSubmissions'));
    }
}