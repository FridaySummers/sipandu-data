<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use Illuminate\Http\Request;

class DinasController extends Controller
{
    public function getStatus()
    {
        return response()->json(Dinas::select('id','nama_dinas','kode_dinas','jumlah_target_data')->get());
    }

    public function show(Dinas $dinas)
    {
        return response()->json($dinas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dinas' => 'required|string|max:255',
            'kode_dinas' => 'required|string|max:255|unique:dinas,kode_dinas',
            'kepala_dinas' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'jumlah_target_data' => 'nullable|integer|min:0',
        ]);

        $dinas = Dinas::create($validated);
        return response()->json($dinas, 201);
    }
}
