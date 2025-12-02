<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\OpdRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpdRowController extends Controller
{
    /**
     * Get all OPD row records based on user permissions and query parameters.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $u = Auth::user();
        if (!$u) return response()->json([], 401);

        $opd = $request->query('opd');
        $key = $request->query('key');

        if (!$opd || !$key) return response()->json([]);

        $dinas = Dinas::where('nama_dinas', $opd)->first();
        if (!$dinas) return response()->json([]);

        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json([], 403);
        }

        // Redirect to special tables for specific keys
        if ($key === 'koperasi_perkembangan' && class_exists(\App\Models\KoperasiRow::class)) {
            $rows = \App\Models\KoperasiRow::where('dinas_id', $dinas->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'uraian' => $r->uraian,
                        'satuan' => $r->satuan,
                        'values' => $r->values ?: []
                    ];
                });
            return response()->json($rows);
        }

        if ($key === 'dpmptsp_investasi' && class_exists(\App\Models\DpmptspInvestasiRow::class)) {
            $rows = \App\Models\DpmptspInvestasiRow::where('dinas_id', $dinas->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'uraian' => $r->uraian,
                        'satuan' => $r->satuan,
                        'values' => $r->values ?: []
                    ];
                });
            return response()->json($rows);
        }

        $rows = OpdRow::where('dinas_id', $dinas->id)
            ->where('table_key', $key)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'uraian' => $r->uraian,
                    'satuan' => $r->satuan,
                    'values' => $r->values ?: []
                ];
            });

        return response()->json($rows);
    }

    /**
     * Store a new OPD row record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $u = Auth::user();
        if (!$u) return response()->json(['error' => 'unauthorized'], 401);
        if (!in_array($u->role, ['super_admin', 'admin_dinas'])) return response()->json(['error' => 'forbidden'], 403);

        $validated = $request->validate([
            'opd' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array'
        ]);

        $dinas = Dinas::where('nama_dinas', $validated['opd'])->first();
        if (!$dinas) return response()->json(['error' => 'opd_not_found'], 422);

        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        // Redirect to special tables for specific keys
        if ($validated['key'] === 'koperasi_perkembangan' && class_exists(\App\Models\KoperasiRow::class)) {
            $row = \App\Models\KoperasiRow::create([
                'dinas_id' => $dinas->id,
                'uraian' => $validated['uraian'],
                'satuan' => $validated['satuan'] ?? null,
                'values' => $validated['values'] ?? []
            ]);
            return response()->json([
                'id' => $row->id,
                'uraian' => $row->uraian,
                'satuan' => $row->satuan,
                'values' => $row->values
            ], 201);
        }

        if ($validated['key'] === 'dpmptsp_investasi' && class_exists(\App\Models\DpmptspInvestasiRow::class)) {
            $row = \App\Models\DpmptspInvestasiRow::create([
                'dinas_id' => $dinas->id,
                'uraian' => $validated['uraian'],
                'satuan' => $validated['satuan'] ?? null,
                'values' => $validated['values'] ?? []
            ]);
            return response()->json([
                'id' => $row->id,
                'uraian' => $row->uraian,
                'satuan' => $row->satuan,
                'values' => $row->values
            ], 201);
        }

        $row = OpdRow::create([
            'dinas_id' => $dinas->id,
            'table_key' => $validated['key'],
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $validated['values'] ?? []
        ]);

        return response()->json([
            'id' => $row->id,
            'uraian' => $row->uraian,
            'satuan' => $row->satuan,
            'values' => $row->values
        ], 201);
    }

    /**
     * Update a specific OPD row record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OpdRow  $row
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, OpdRow $row)
    {
        $u = Auth::user();
        if (!$u) return response()->json(['error' => 'unauthorized'], 401);
        if (!in_array($u->role, ['super_admin', 'admin_dinas'])) return response()->json(['error' => 'forbidden'], 403);
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) return response()->json(['error' => 'forbidden'], 403);

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array'
        ]);

        // If the row has a special key that maps to a dedicated model, redirect to the appropriate dedicated model update
        if ($row->table_key === 'koperasi_perkembangan' && class_exists(\App\Models\KoperasiRow::class)) {
            $specialModel = \App\Models\KoperasiRow::where('id', $row->id)->first();
            if ($specialModel) {
                $specialModel->update([
                    'uraian' => $validated['uraian'],
                    'satuan' => $validated['satuan'] ?? null,
                    'values' => $validated['values'] ?? []
                ]);

                return response()->json([
                    'id' => $specialModel->id,
                    'uraian' => $specialModel->uraian,
                    'satuan' => $specialModel->satuan,
                    'values' => $specialModel->values
                ]);
            }
        } elseif ($row->table_key === 'dpmptsp_investasi' && class_exists(\App\Models\DpmptspInvestasiRow::class)) {
            $specialModel = \App\Models\DpmptspInvestasiRow::where('id', $row->id)->first();
            if ($specialModel) {
                $specialModel->update([
                    'uraian' => $validated['uraian'],
                    'satuan' => $validated['satuan'] ?? null,
                    'values' => $validated['values'] ?? []
                ]);

                return response()->json([
                    'id' => $specialModel->id,
                    'uraian' => $specialModel->uraian,
                    'satuan' => $specialModel->satuan,
                    'values' => $specialModel->values
                ]);
            }
        }

        $row->update([
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $validated['values'] ?? []
        ]);

        return response()->json([
            'id' => $row->id,
            'uraian' => $row->uraian,
            'satuan' => $row->satuan,
            'values' => $row->values
        ]);
    }

    /**
     * Delete a specific OPD row record.
     *
     * @param  \App\Models\OpdRow  $row
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OpdRow $row)
    {
        $u = Auth::user();
        if (!$u) return response()->json(['error' => 'unauthorized'], 401);
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) return response()->json(['error' => 'forbidden'], 403);

        $row->delete();
        return response()->json(['success' => true]);
    }
}