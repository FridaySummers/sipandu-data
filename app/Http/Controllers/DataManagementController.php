<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataManagementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data akan difilter otomatis oleh scope ByUserRole
        $data = DataSubmission::byUserRole()
                    ->with(['user', 'dinas'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('datamanagement', [
            'data' => $data,
            'user' => $user
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $dinasOptions = [];

        // Super Admin bisa pilih dari semua dinas
        if ($user->isSuperAdmin()) {
            $dinasOptions = Dinas::all();
        }

        return view('datamanagement.create', [
            'dinasOptions' => $dinasOptions,
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi dasar
        $validated = $request->validate([
            'judul_data' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tahun_perencanaan' => 'required|string|size:4',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'dinas_id' => 'required|exists:dinas,id',
            'structured_data' => 'nullable|array',
        ]);

        // Handle file upload (OPSIONAL) - PASTIKAN TIDAK NULL
        $filePath = ''; // Default string kosong, bukan null
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
        }

        // SPECIAL CASE: Handle multiple structured data submissions untuk DPMPTSP
        if ($request->has('structured_data') && is_array($request->structured_data)) {
            foreach ($request->structured_data as $data) {
                // Validasi data structured
                if (empty($data['judul_data']) || empty($data['tipe'])) {
                    continue; // Skip data yang tidak valid
                }

                DataSubmission::create([
                    'judul_data' => $data['judul_data'],
                    'deskripsi' => 'Data Investasi ' . ($data['tipe'] ?? ''),
                    'structured_data' => [
                        'tipe' => $data['tipe'] ?? '',
                        'y2025' => $data['y2025'] ?? '',
                        'y2026' => $data['y2026'] ?? '',
                        'y2027' => $data['y2027'] ?? '',
                        'y2028' => $data['y2028'] ?? '',
                        'y2029' => $data['y2029'] ?? '',
                    ],
                    'file_path' => $filePath, // PASTIKAN string kosong jika tidak ada file
                    'tahun_perencanaan' => $request->tahun_perencanaan,
                    'user_id' => $user->id,
                    'dinas_id' => $request->dinas_id,
                    'status' => 'pending',
                ]);
            }
            
            return redirect()->route('dinas.show', $request->dinas_id)
                ->with('success', 'Data berhasil disimpan!');
        }

        // DEFAULT CASE: Single data submission untuk dinas lainnya
        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';
        $validated['file_path'] = $filePath; // PASTIKAN string kosong jika tidak ada file

        // Handle judul_data untuk single submission
        if (empty($validated['judul_data'])) {
            $validated['judul_data'] = 'Data ' . Dinas::find($request->dinas_id)->nama_dinas;
        }

        DataSubmission::create($validated);

        return redirect()->route('dinas.show', $request->dinas_id)
            ->with('success', 'Data berhasil disimpan!');
    }

    public function edit(DataSubmission $dataSubmission)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->isUser() && $dataSubmission->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->isAdminDinas() && $dataSubmission->dinas_id !== $user->dinas_id) {
            abort(403, 'Unauthorized action.');
        }

        $dinasOptions = [];
        if ($user->isSuperAdmin()) {
            $dinasOptions = Dinas::all();
        }

        return view('datamanagement.edit', [
            'data' => $dataSubmission,
            'dinasOptions' => $dinasOptions,
            'user' => $user
        ]);
    }

    public function update(Request $request, DataSubmission $dataSubmission)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->isUser() && $dataSubmission->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->isAdminDinas() && $dataSubmission->dinas_id !== $user->dinas_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'judul_data' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tahun_perencanaan' => 'required|string|size:4',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
        ]);

        // Super admin bisa ganti dinas, lainnya tidak
        if ($user->isSuperAdmin()) {
            $validated['dinas_id'] = $request->dinas_id;
        }

        // Handle file upload (OPSIONAL)
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($dataSubmission->file_path) {
                Storage::disk('public')->delete($dataSubmission->file_path);
            }

            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $validated['file_path'] = $filePath;
        } else {
            // Keep existing file if no new file uploaded
            $validated['file_path'] = $dataSubmission->file_path;
        }

        $dataSubmission->update($validated);

        // Redirect ke halaman dinas yang sesuai
        return redirect()->route('dinas.show', $dataSubmission->dinas_id)
            ->with('success', 'Data berhasil diupdate!');
    }

    public function destroy(DataSubmission $dataSubmission)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->isUser() && $dataSubmission->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->isAdminDinas() && $dataSubmission->dinas_id !== $user->dinas_id) {
            abort(403, 'Unauthorized action.');
        }

        $dinasId = $dataSubmission->dinas_id; // Simpan dinas_id untuk redirect

        // Delete file if exists
        if ($dataSubmission->file_path) {
            Storage::disk('public')->delete($dataSubmission->file_path);
        }

        $dataSubmission->delete();

        return redirect()->route('dinas.show', $dinasId)
            ->with('success', 'Data berhasil dihapus!');
    }

    // Approval methods untuk Admin Dinas dan Super Admin
    public function approve(DataSubmission $dataSubmission)
    {
        $user = Auth::user();

        if (!$user->isAdminDinas() && !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Admin Dinas hanya bisa approve data di dinasnya
        if ($user->isAdminDinas() && $dataSubmission->dinas_id !== $user->dinas_id) {
            abort(403, 'Anda hanya bisa menyetujui data dari dinas Anda sendiri.');
        }

        $dataSubmission->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return redirect()->route('dinas.show', $dataSubmission->dinas_id)
            ->with('success', 'Data berhasil disetujui!');
    }

    public function reject(Request $request, DataSubmission $dataSubmission)
    {
        $user = Auth::user();

        if (!$user->isAdminDinas() && !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Admin Dinas hanya bisa reject data di dinasnya
        if ($user->isAdminDinas() && $dataSubmission->dinas_id !== $user->dinas_id) {
            abort(403, 'Anda hanya bisa menolak data dari dinas Anda sendiri.');
        }

        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $dataSubmission->update([
            'status' => 'rejected',
            'catatan_revisi' => $request->catatan_revisi,
            'rejected_by' => $user->id,
            'rejected_at' => now(),
        ]);

        return redirect()->route('dinas.show', $dataSubmission->dinas_id)
            ->with('success', 'Data berhasil ditolak!');
    }
}