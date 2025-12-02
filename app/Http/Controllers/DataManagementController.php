<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use App\Models\DmRecord;
use App\Models\DlhRow;
use App\Models\OpdRow;
use App\Models\KetahananPanganRow;
use App\Models\PerikananInfrastrukturRow;
use App\Models\PerikananAlatTangkapRow;
use App\Models\PerikananBudidayaRow;
use App\Models\PerikananProduksiRow;
use App\Models\PerikananBinaKelompokRow;
use App\Models\PariwisataAkomodasiRow;
use App\Models\PariwisataWisatawanRow;
use App\Models\PariwisataObjekJenisRow;
use App\Models\PariwisataObjekKecamatanRow;
use App\Models\PariwisataPemanduRow;
use App\Models\TanamanPanganSayurRow;
use App\Models\TanamanPanganKelompokRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user() && Auth::user()->role === 'user') {
            return redirect()->route('dashboard');
        }

        // Get submission statistics
        $totalSubmissions = DataSubmission::count();
        $completeSubmissions = DataSubmission::where('status', 'approved')->count();
        $inProgressSubmissions = DataSubmission::where('status', 'in_review')->count();
        $pendingReviews = DataSubmission::where('status', 'pending')->count();

        $submissions = class_exists(DataSubmission::class) ? DataSubmission::paginate(15) : null;
        $pending = null;
        if (class_exists(DataSubmission::class)) {
            $q = DataSubmission::where('status', 'pending');
            $u = Auth::user();
            if ($u && $u->role === 'admin_dinas' && $u->dinas_id) {
                $q->where('dinas_id', $u->dinas_id);
            }
            $pending = $q->orderBy('created_at', 'desc')->limit(20)->get();
        }

        $approvedRecords = null;
        if (class_exists(DmRecord::class)) {
            $approvedRecords = DmRecord::with('dinas')->orderBy('created_at','desc')->limit(100)->get();
        }

        return view('datamanagement', [
            'submissions' => $submissions,
            'pendingSubmissions' => $pending,
            'approvedRecords' => $approvedRecords,
            'totalSubmissions' => $totalSubmissions,
            'completeSubmissions' => $completeSubmissions,
            'inProgressSubmissions' => $inProgressSubmissions,
            'pendingReviews' => $pendingReviews
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_data' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_path' => 'required|string',
            'tahun_perencanaan' => 'required|string|size:4',
            'opd' => 'nullable|string|max:255',
            'dinas_id' => 'nullable|integer',
        ]);

        $u = Auth::user();
        if (!$u) { return redirect()->route('login'); }

        $dinasId = $validated['dinas_id'] ?? null;
        if (!$dinasId && !empty($validated['opd'])) {
            $d = \App\Models\Dinas::where('nama_dinas', $validated['opd'])->first();
            if ($d) { $dinasId = $d->id; }
        }
        if (!$dinasId) { $dinasId = $u->dinas_id; }

        if (class_exists(DataSubmission::class)) {
            $submission = DataSubmission::create([
                'user_id' => $u->id,
                'dinas_id' => $dinasId,
                'judul_data' => $validated['judul_data'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'file_path' => $validated['file_path'],
                'tahun_perencanaan' => $validated['tahun_perencanaan'],
                'status' => 'pending',
            ]);
            if (class_exists(DmRecord::class)) {
                try { $hasSubmissionId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','submission_id'); } catch (\Throwable $__) { $hasSubmissionId = true; }
                try { $hasDinasId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','dinas_id'); } catch (\Throwable $___) { $hasDinasId = false; }
                try { $hasName = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','name'); } catch (\Throwable $____) { $hasName = false; }
                try { $hasPeriod = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','period'); } catch (\Throwable $_____) { $hasPeriod = false; }
                $exists = false;
                if ($hasSubmissionId) {
                    $exists = DmRecord::where('submission_id', $submission->id)->exists();
                } else {
                    $q = DmRecord::query();
                    if ($hasDinasId) { $q->where('dinas_id', $dinasId); }
                    if ($hasName) { $q->where('name', $validated['judul_data']); }
                    if ($hasPeriod) { $q->where('period', $validated['tahun_perencanaan']); }
                    $exists = $q->exists();
                }
                if (!$exists) {
                    $payload = [ 'status' => 'Pending', 'pic' => $u->name ?? null ];
                    if ($hasDinasId) { $payload['dinas_id'] = $dinasId; }
                    if ($hasName) { $payload['name'] = $validated['judul_data']; }
                    if ($hasPeriod) { $payload['period'] = $validated['tahun_perencanaan']; }
                    if ($hasSubmissionId) { $payload['submission_id'] = $submission->id; }
                    DmRecord::create($payload);
                }
            }
        }

        // Untuk fetch AJAX, kembalikan JSON jika diminta
        if ($request->expectsJson()) {
            return response()->json(['success'=>true]);
        }
        return redirect()->back()->with('success', 'Pengajuan dikirim');
    }

    public function update(Request $request, DataSubmission $submission)
    {
        $this->authorize('update', $submission);

        $validated = $request->validate([
            'judul_data' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_path' => 'required|string',
            'tahun_perencanaan' => 'required|string|size:4',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $submission->update($validated);

        return redirect()->route('datamanagement')->with('success', 'Data updated successfully');
    }

    public function approve(Request $request, DataSubmission $submission)
    {
        $u = Auth::user();
        if (!$u) { return redirect()->route('login'); }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $submission->dinas_id !== $u->dinas_id) {
            return redirect()->route('datamanagement')->with('error', 'Tidak berhak menyetujui');
        }
        $submission->status = 'approved';
        $submission->save();

        if (class_exists(DmRecord::class)) {
            try { $hasSubmissionId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','submission_id'); } catch (\Throwable $__) { $hasSubmissionId = true; }
            try { $hasDinasId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','dinas_id'); } catch (\Throwable $___) { $hasDinasId = false; }
            try { $hasName = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','name'); } catch (\Throwable $____) { $hasName = false; }
            try { $hasPeriod = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','period'); } catch (\Throwable $_____) { $hasPeriod = false; }

            $exists = false;
            if ($hasSubmissionId) {
                $exists = DmRecord::where('submission_id', $submission->id)->exists();
            } else {
                $q = DmRecord::query();
                if ($hasDinasId) { $q->where('dinas_id', $submission->dinas_id); }
                if ($hasName) { $q->where('name', $submission->judul_data); }
                if ($hasPeriod) { $q->where('period', $submission->tahun_perencanaan); }
                $exists = $q->exists();
            }

            if ($exists) {
                $rec = $hasSubmissionId ? DmRecord::where('submission_id', $submission->id)->first() : DmRecord::where(function($qq) use($hasDinasId,$hasName,$hasPeriod,$submission){
                    if ($hasDinasId) { $qq->where('dinas_id', $submission->dinas_id); }
                    if ($hasName) { $qq->where('name', $submission->judul_data); }
                    if ($hasPeriod) { $qq->where('period', $submission->tahun_perencanaan); }
                })->first();
                if ($rec) { $rec->update(['status'=>'Approved','pic'=>Auth::user()->name ?? null]); }
            } else {
                $payload = [ 'status' => 'Approved', 'pic' => Auth::user()->name ?? null ];
                if ($hasDinasId) { $payload['dinas_id'] = $submission->dinas_id; }
                if ($hasName) { $payload['name'] = $submission->judul_data; }
                if ($hasPeriod) { $payload['period'] = $submission->tahun_perencanaan; }
                if ($hasSubmissionId) { $payload['submission_id'] = $submission->id; }
                DmRecord::create($payload);
            }

            // Auto insert ke tabel Perdagangan per dinas saat ACC
            try {
                $fp = (string)$submission->file_path;
                if (stripos($fp, 'perdagangan_pdrb') !== false && class_exists(\App\Models\PerdaganganPdrbRow::class)) {
                    if (!\App\Models\PerdaganganPdrbRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerdaganganPdrbRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perdagangan_ekspor') !== false && class_exists(\App\Models\PerdaganganEksporRow::class)) {
                    if (!\App\Models\PerdaganganEksporRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerdaganganEksporRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'koperasi') !== false && class_exists(\App\Models\KoperasiRow::class)) {
                    if (!\App\Models\KoperasiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\KoperasiRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'dpmptsp') !== false && class_exists(\App\Models\DpmptspInvestasiRow::class)) {
                    if (!\App\Models\DpmptspInvestasiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\DpmptspInvestasiRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perindustrian_hb') !== false && class_exists(\App\Models\PerindustrianHbRow::class)) {
                    if (!\App\Models\PerindustrianHbRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerindustrianHbRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perindustrian_hk') !== false && class_exists(\App\Models\PerindustrianHkRow::class)) {
                    if (!\App\Models\PerindustrianHkRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerindustrianHkRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perindustrian_growth') !== false && class_exists(\App\Models\PerindustrianGrowthRow::class)) {
                    if (!\App\Models\PerindustrianGrowthRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerindustrianGrowthRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perkebunan_pop') !== false && class_exists(\App\Models\PerkebunanPopRow::class)) {
                    if (!\App\Models\PerkebunanPopRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerkebunanPopRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perkebunan_prod') !== false && class_exists(\App\Models\PerkebunanProdRow::class)) {
                    if (!\App\Models\PerkebunanProdRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerkebunanProdRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perkebunan_luas') !== false && class_exists(\App\Models\PerkebunanLuasRow::class)) {
                    if (!\App\Models\PerkebunanLuasRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerkebunanLuasRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perikanan_inf') !== false && class_exists(\App\Models\PerikananInfrastrukturRow::class)) {
                    if (!\App\Models\PerikananInfrastrukturRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananInfrastrukturRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perikanan_alt') !== false && class_exists(\App\Models\PerikananAlatTangkapRow::class)) {
                    if (!\App\Models\PerikananAlatTangkapRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananAlatTangkapRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perikanan_bud') !== false && class_exists(\App\Models\PerikananBudidayaRow::class)) {
                    if (!\App\Models\PerikananBudidayaRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananBudidayaRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perikanan_pro') !== false && class_exists(\App\Models\PerikananProduksiRow::class)) {
                    if (!\App\Models\PerikananProduksiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananProduksiRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'perikanan_bin') !== false && class_exists(\App\Models\PerikananBinaKelompokRow::class)) {
                    if (!\App\Models\PerikananBinaKelompokRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananBinaKelompokRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'ketahanan_pangan') !== false && class_exists(\App\Models\KetahananPanganRow::class)) {
                    if (!\App\Models\KetahananPanganRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\KetahananPanganRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'pariwisata_ako') !== false && class_exists(\App\Models\PariwisataAkomodasiRow::class)) {
                    if (!\App\Models\PariwisataAkomodasiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataAkomodasiRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'pariwisata_wis') !== false && class_exists(\App\Models\PariwisataWisatawanRow::class)) {
                    if (!\App\Models\PariwisataWisatawanRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataWisatawanRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'pariwisata_jen') !== false && class_exists(\App\Models\PariwisataObjekJenisRow::class)) {
                    if (!\App\Models\PariwisataObjekJenisRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataObjekJenisRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'pariwisata_kec') !== false && class_exists(\App\Models\PariwisataObjekKecamatanRow::class)) {
                    if (!\App\Models\PariwisataObjekKecamatanRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataObjekKecamatanRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'pariwisata_pem') !== false && class_exists(\App\Models\PariwisataPemanduRow::class)) {
                    if (!\App\Models\PariwisataPemanduRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataPemanduRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'values'=>[]]);
                    }
                }
                if (stripos($fp, 'dlh_inline') !== false && class_exists(\App\Models\DlhRow::class)) {
                    if (!\App\Models\DlhRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\DlhRow::create(['dinas_id'=>$submission->dinas_id,'uraian'=>$submission->judul_data,'satuan'=>null,'y2019'=>null,'y2020'=>null,'y2021'=>null,'y2022'=>null,'y2023'=>null]);
                    }
                }
            } catch (\Throwable $__) {}
        }
        return redirect()->route('datamanagement')->with('success', 'Pengajuan disetujui');
    }


    public function reject(Request $request, DataSubmission $submission)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) { return redirect()->route('login'); }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $submission->dinas_id !== $u->dinas_id) {
            return redirect()->route('datamanagement')->with('error', 'Tidak berhak menolak');
        }
        $submission->status = 'rejected';
        $submission->catatan_revisi = $request->input('catatan_revisi');
        $submission->save();
        if (class_exists(DmRecord::class)) {
            try { $hasSubmissionId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','submission_id'); } catch (\Throwable $__) { $hasSubmissionId = true; }
            try { $hasDinasId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','dinas_id'); } catch (\Throwable $___) { $hasDinasId = false; }
            try { $hasName = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','name'); } catch (\Throwable $____) { $hasName = false; }
            try { $hasPeriod = \Illuminate\Support\Facades\Schema::hasColumn('dm_records','period'); } catch (\Throwable $_____) { $hasPeriod = false; }
            $rec = $hasSubmissionId ? DmRecord::where('submission_id', $submission->id)->first() : DmRecord::where(function($qq) use($hasDinasId,$hasName,$hasPeriod,$submission){
                if ($hasDinasId) { $qq->where('dinas_id', $submission->dinas_id); }
                if ($hasName) { $qq->where('name', $submission->judul_data); }
                if ($hasPeriod) { $qq->where('period', $submission->tahun_perencanaan); }
            })->first();
            if ($rec) { $rec->update(['status'=>'Rejected','pic'=>$u->name ?? null]); }
        }
        return redirect()->route('datamanagement')->with('success', 'Pengajuan ditolak');
    }

    // Missing methods restored for compatibility
    public function records(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json([], 401);
        $q = DmRecord::with('dinas');
        if ($u->role === 'admin_dinas' && $u->dinas_id) { $q->where('dinas_id', $u->dinas_id); }
        $status = $request->query('status'); if ($status) { $q->where('status', $status); }
        $opd = $request->query('opd'); if ($opd) { $q->whereHas('dinas', function($qq) use ($opd){ $qq->where('nama_dinas', $opd); }); }
        $rows = $q->orderBy('created_at','desc')->get()->map(function($r){
            return [
                'id' => $r->id,
                'opd' => optional($r->dinas)->nama_dinas,
                'name' => $r->name,
                'period' => $r->period,
                'status' => $r->status,
                'pic' => $r->pic,
                'createdAt' => optional($r->created_at)->toIso8601String(),
            ];
        });
        return response()->json($rows);
    }

    public function storeRecord(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json(['error'=>'unauthorized'], 401);
        if (!in_array($u->role, ['super_admin','admin_dinas'])) {
            return response()->json(['error'=>'forbidden'], 403);
        }

        $validated = $request->validate([
            'opd' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'period' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,In Review,Approved,Rejected',
            'pic' => 'nullable|string|max:255',
        ]);

        $dinas = \App\Models\Dinas::where('nama_dinas', $validated['opd'])->first();
        if (!$dinas) {
            return response()->json(['error'=>'opd_not_found'], 422);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json(['error'=>'forbidden'], 403);
        }

        $rec = DmRecord::create([
            'dinas_id' => $dinas->id,
            'name' => $validated['name'],
            'period' => $validated['period'] ?? null,
            'status' => $validated['status'],
            'pic' => $validated['pic'] ?? ($u->name ?? null),
        ]);
        return response()->json([
            'id' => $rec->id,
            'opd' => $dinas->nama_dinas,
            'name' => $rec->name,
            'period' => $rec->period,
            'status' => $rec->status,
            'pic' => $rec->pic,
            'createdAt' => optional($rec->created_at)->toIso8601String(),
        ], 201);
    }

    public function updateRecord(Request $request, DmRecord $record)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json(['error'=>'unauthorized'], 401);
        if (!in_array($u->role, ['super_admin','admin_dinas'])) {
            return response()->json(['error'=>'forbidden'], 403);
        }

        $validated = $request->validate([
            'opd' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'period' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,In Review,Approved,Rejected',
            'pic' => 'nullable|string|max:255',
        ]);

        $dinas = \App\Models\Dinas::where('nama_dinas', $validated['opd'])->first();
        if (!$dinas) {
            return response()->json(['error'=>'opd_not_found'], 422);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json(['error'=>'forbidden'], 403);
        }

        $record->update([
            'dinas_id' => $dinas->id,
            'name' => $validated['name'],
            'period' => $validated['period'] ?? null,
            'status' => $validated['status'],
            'pic' => $validated['pic'] ?? ($u->name ?? null),
        ]);

        return response()->json([
            'id' => $record->id,
            'opd' => $dinas->nama_dinas,
            'name' => $record->name,
            'period' => $record->period,
            'status' => $record->status,
            'pic' => $record->pic,
            'createdAt' => optional($record->created_at)->toIso8601String(),
        ]);
    }

    public function destroyRecord(DmRecord $record)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json(['error'=>'unauthorized'], 401);
        if ($u->role === 'admin_dinas' && $u->dinas_id && $record->dinas_id !== $u->dinas_id) {
            return response()->json(['error'=>'forbidden'], 403);
        }
        $record->delete();
        return response()->json(['success'=>true]);
    }

    public function dlhRows(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json([], 401);
        $dlh = \App\Models\Dinas::where('nama_dinas', 'DLH')->first() ?: \App\Models\Dinas::where('nama_dinas', 'Dinas Lingkungan Hidup')->first();
        if (!$dlh) return response()->json([]);
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dlh->id) {
            return response()->json([], 403);
        }
        $rows = DlhRow::where('dinas_id', $dlh->id)->orderBy('created_at', 'desc')->get()->map(function($r){
            return [
                'id' => $r->id,
                'no' => null,
                'uraian' => $r->uraian,
                'satuan' => $r->satuan,
                'y2019' => $r->y2019,
                'y2020' => $r->y2020,
                'y2021' => $r->y2021,
                'y2022' => $r->y2022,
                'y2023' => $r->y2023,
            ];
        });
        return response()->json($rows);
    }

    public function dlhStoreRow(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json(['error' => 'unauthorized'], 401);
        if (!in_array($u->role, ['super_admin', 'admin_dinas'])) return response()->json(['error' => 'forbidden'], 403);
        $dlh = \App\Models\Dinas::where('nama_dinas', 'DLH')->first() ?: \App\Models\Dinas::where('nama_dinas', 'Dinas Lingkungan Hidup')->first();
        if (!$dlh) return response()->json(['error' => 'dlh_not_found'], 404);
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dlh->id) return response()->json(['error' => 'forbidden'], 403);

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'y2019' => 'nullable|string|max:255',
            'y2020' => 'nullable|string|max:255',
            'y2021' => 'nullable|string|max:255',
            'y2022' => 'nullable|string|max:255',
            'y2023' => 'nullable|string|max:255',
        ]);

        $row = DlhRow::create(array_merge($validated, [ 'dinas_id' => $dlh->id ]));
        return response()->json([ 'id' => $row->id ] + $validated, 201);
    }

    public function dlhUpdateRow(Request $request, DlhRow $row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json(['error' => 'unauthorized'], 401);
        if (!in_array($u->role, ['super_admin', 'admin_dinas'])) return response()->json(['error' => 'forbidden'], 403);
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) return response()->json(['error' => 'forbidden'], 403);

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'y2019' => 'nullable|string|max:255',
            'y2020' => 'nullable|string|max:255',
            'y2021' => 'nullable|string|max:255',
            'y2022' => 'nullable|string|max:255',
            'y2023' => 'nullable|string|max:255',
        ]);

        $row->update($validated);
        return response()->json([ 'id' => $row->id ] + $validated);
    }

    public function dlhDestroyRow(DlhRow $row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) return response()->json(['error' => 'unauthorized'], 401);
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) return response()->json(['error' => 'forbidden'], 403);
        $row->delete();
        return response()->json(['success' => true]);
    }

    // Generic Methods for various dinas
    public function ketapangRows() { return $this->genericRows(KetahananPanganRow::class); }
    public function ketapangStoreRow(Request $r) { return $this->genericStore($r, KetahananPanganRow::class); }
    public function ketapangUpdateRow(Request $r, KetahananPanganRow $row) { return $this->genericUpdate($r, $row); }
    public function ketapangDestroyRow(KetahananPanganRow $row) { return $this->genericDestroy($row); }

    public function perikananInfRows() { return $this->genericRows(PerikananInfrastrukturRow::class); }
    public function perikananInfStoreRow(Request $r) { return $this->genericStore($r, PerikananInfrastrukturRow::class); }
    public function perikananInfUpdateRow(Request $r, PerikananInfrastrukturRow $row) { return $this->genericUpdate($r, $row); }
    public function perikananInfDestroyRow(PerikananInfrastrukturRow $row) { return $this->genericDestroy($row); }

    public function perikananAltRows() { return $this->genericRows(PerikananAlatTangkapRow::class); }
    public function perikananAltStoreRow(Request $r) { return $this->genericStore($r, PerikananAlatTangkapRow::class); }
    public function perikananAltUpdateRow(Request $r, PerikananAlatTangkapRow $row) { return $this->genericUpdate($r, $row); }
    public function perikananAltDestroyRow(PerikananAlatTangkapRow $row) { return $this->genericDestroy($row); }

    public function perikananBudRows() { return $this->genericRows(PerikananBudidayaRow::class); }
    public function perikananBudStoreRow(Request $r) { return $this->genericStore($r, PerikananBudidayaRow::class); }
    public function perikananBudUpdateRow(Request $r, PerikananBudidayaRow $row) { return $this->genericUpdate($r, $row); }
    public function perikananBudDestroyRow(PerikananBudidayaRow $row) { return $this->genericDestroy($row); }

    public function perikananProRows() { return $this->genericRows(PerikananProduksiRow::class); }
    public function perikananProStoreRow(Request $r) { return $this->genericStore($r, PerikananProduksiRow::class); }
    public function perikananProUpdateRow(Request $r, PerikananProduksiRow $row) { return $this->genericUpdate($r, $row); }
    public function perikananProDestroyRow(PerikananProduksiRow $row) { return $this->genericDestroy($row); }

    public function perikananBinRows() { return $this->genericRows(PerikananBinaKelompokRow::class); }
    public function perikananBinStoreRow(Request $r) { return $this->genericStore($r, PerikananBinaKelompokRow::class); }
    public function perikananBinUpdateRow(Request $r, PerikananBinaKelompokRow $row) { return $this->genericUpdate($r, $row); }
    public function perikananBinDestroyRow(PerikananBinaKelompokRow $row) { return $this->genericDestroy($row); }

    public function pariwisataAkoRows() { return $this->genericRows(PariwisataAkomodasiRow::class); }
    public function pariwisataAkoStoreRow(Request $r) { return $this->genericStore($r, PariwisataAkomodasiRow::class); }
    public function pariwisataAkoUpdateRow(Request $r, PariwisataAkomodasiRow $row) { return $this->genericUpdate($r, $row); }
    public function pariwisataAkoDestroyRow(PariwisataAkomodasiRow $row) { return $this->genericDestroy($row); }

    public function pariwisataWisRows() { return $this->genericRows(PariwisataWisatawanRow::class); }
    public function pariwisataWisStoreRow(Request $r) { return $this->genericStore($r, PariwisataWisatawanRow::class); }
    public function pariwisataWisUpdateRow(Request $r, PariwisataWisatawanRow $row) { return $this->genericUpdate($r, $row); }
    public function pariwisataWisDestroyRow(PariwisataWisatawanRow $row) { return $this->genericDestroy($row); }

    public function pariwisataJenRows() { return $this->genericRows(PariwisataObjekJenisRow::class); }
    public function pariwisataJenStoreRow(Request $r) { return $this->genericStore($r, PariwisataObjekJenisRow::class); }
    public function pariwisataJenUpdateRow(Request $r, PariwisataObjekJenisRow $row) { return $this->genericUpdate($r, $row); }
    public function pariwisataJenDestroyRow(PariwisataObjekJenisRow $row) { return $this->genericDestroy($row); }

    public function pariwisataKecRows() { return $this->genericRows(PariwisataObjekKecamatanRow::class); }
    public function pariwisataKecStoreRow(Request $r) { return $this->genericStore($r, PariwisataObjekKecamatanRow::class); }
    public function pariwisataKecUpdateRow(Request $r, PariwisataObjekKecamatanRow $row) { return $this->genericUpdate($r, $row); }
    public function pariwisataKecDestroyRow(PariwisataObjekKecamatanRow $row) { return $this->genericDestroy($row); }

    public function pariwisataPemRows() { return $this->genericRows(PariwisataPemanduRow::class); }
    public function pariwisataPemStoreRow(Request $r) { return $this->genericStore($r, PariwisataPemanduRow::class); }
    public function pariwisataPemUpdateRow(Request $r, PariwisataPemanduRow $row) { return $this->genericUpdate($r, $row); }
    public function pariwisataPemDestroyRow(PariwisataPemanduRow $row) { return $this->genericDestroy($row); }

    public function perdaganganPdrbRows() { return $this->genericRows(\App\Models\PerdaganganPdrbRow::class); }
    public function perdaganganPdrbStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerdaganganPdrbRow::class); }
    public function perdaganganPdrbUpdateRow(Request $r, \App\Models\PerdaganganPdrbRow $row) { return $this->genericUpdate($r, $row); }
    public function perdaganganPdrbDestroyRow(\App\Models\PerdaganganPdrbRow $row) { return $this->genericDestroy($row); }

    public function perdaganganEksRows() { return $this->genericRows(\App\Models\PerdaganganEksporRow::class); }
    public function perdaganganEksStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerdaganganEksporRow::class); }
    public function perdaganganEksUpdateRow(Request $r, \App\Models\PerdaganganEksporRow $row) { return $this->genericUpdate($r, $row); }
    public function perdaganganEksDestroyRow(\App\Models\PerdaganganEksporRow $row) { return $this->genericDestroy($row); }

    public function perkebunanPopRows() { return $this->genericRows(\App\Models\PerkebunanPopRow::class); }
    public function perkebunanPopStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerkebunanPopRow::class); }
    public function perkebunanPopUpdateRow(Request $r, \App\Models\PerkebunanPopRow $row) { return $this->genericUpdate($r, $row); }
    public function perkebunanPopDestroyRow(\App\Models\PerkebunanPopRow $row) { return $this->genericDestroy($row); }

    public function perkebunanProdRows() { return $this->genericRows(\App\Models\PerkebunanProdRow::class); }
    public function perkebunanProdStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerkebunanProdRow::class); }
    public function perkebunanProdUpdateRow(Request $r, \App\Models\PerkebunanProdRow $row) { return $this->genericUpdate($r, $row); }
    public function perkebunanProdDestroyRow(\App\Models\PerkebunanProdRow $row) { return $this->genericDestroy($row); }

    public function perkebunanLuasRows() { return $this->genericRows(\App\Models\PerkebunanLuasRow::class); }
    public function perkebunanLuasStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerkebunanLuasRow::class); }
    public function perkebunanLuasUpdateRow(Request $r, \App\Models\PerkebunanLuasRow $row) { return $this->genericUpdate($r, $row); }
    public function perkebunanLuasDestroyRow(\App\Models\PerkebunanLuasRow $row) { return $this->genericDestroy($row); }

    public function koperasiRows(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) { return response()->json([], 401); }

        $q = \App\Models\OpdRow::where('table_key', 'koperasi_perkembangan');
        if ($u->role === 'admin_dinas' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id);
        }

        $rows = $q->orderBy('created_at', 'desc')
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

    public function koperasiStoreRow(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) { return response()->json(['error' => 'unauthorized'], 401); }
        if (!in_array($u->role, ['super_admin', 'admin_dinas'])) { return response()->json(['error' => 'forbidden'], 403); }

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array'
        ]);

        $row = \App\Models\OpdRow::create([
            'dinas_id' => $u->dinas_id,
            'table_key' => 'koperasi_perkembangan',
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

    public function koperasiUpdateRow(Request $request, \App\Models\OpdRow $row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) { return response()->json(['error' => 'unauthorized'], 401); }
        if (!in_array($u->role, ['super_admin', 'admin_dinas'])) { return response()->json(['error' => 'forbidden'], 403); }

        // Ensure this is a koperasi row
        if ($row->table_key !== 'koperasi_perkembangan') {
            return response()->json(['error' => 'invalid_row'], 400);
        }

        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array'
        ]);

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

    public function koperasiDestroyRow(\App\Models\OpdRow $row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (!$u) { return response()->json(['error' => 'unauthorized'], 401); }

        // Ensure this is a koperasi row
        if ($row->table_key !== 'koperasi_perkembangan') {
            return response()->json(['error' => 'invalid_row'], 400);
        }

        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $row->delete();
        return response()->json(['success' => true]);
    }

    public function dpmptspRows() { return $this->genericRows(\App\Models\DpmptspInvestasiRow::class); }
    public function dpmptspStoreRow(Request $r) { return $this->genericStore($r, \App\Models\DpmptspInvestasiRow::class); }
    public function dpmptspUpdateRow(Request $r, \App\Models\DpmptspInvestasiRow $row) { return $this->genericUpdate($r, $row); }
    public function dpmptspDestroyRow(\App\Models\DpmptspInvestasiRow $row) { return $this->genericDestroy($row); }

    public function perindustrianHbRows() { return $this->genericRows(\App\Models\PerindustrianHbRow::class); }
    public function perindustrianHbStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerindustrianHbRow::class); }
    public function perindustrianHbUpdateRow(Request $r, \App\Models\PerindustrianHbRow $row) { return $this->genericUpdate($r, $row); }
    public function perindustrianHbDestroyRow(\App\Models\PerindustrianHbRow $row) { return $this->genericDestroy($row); }

    public function perindustrianHkRows() { return $this->genericRows(\App\Models\PerindustrianHkRow::class); }
    public function perindustrianHkStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerindustrianHkRow::class); }
    public function perindustrianHkUpdateRow(Request $r, \App\Models\PerindustrianHkRow $row) { return $this->genericUpdate($r, $row); }
    public function perindustrianHkDestroyRow(\App\Models\PerindustrianHkRow $row) { return $this->genericDestroy($row); }

    public function perindustrianGrRows() { return $this->genericRows(\App\Models\PerindustrianGrowthRow::class); }
    public function perindustrianGrStoreRow(Request $r) { return $this->genericStore($r, \App\Models\PerindustrianGrowthRow::class); }
    public function perindustrianGrUpdateRow(Request $r, \App\Models\PerindustrianGrowthRow $row) { return $this->genericUpdate($r, $row); }
    public function perindustrianGrDestroyRow(\App\Models\PerindustrianGrowthRow $row) { return $this->genericDestroy($row); }

    public function tanamanSayurRows() { return $this->genericRows(TanamanPanganSayurRow::class); }
    public function tanamanSayurStoreRow(Request $r) { return $this->genericStore($r, TanamanPanganSayurRow::class); }
    public function tanamanSayurUpdateRow(Request $r, TanamanPanganSayurRow $row) { return $this->genericUpdate($r, $row); }
    public function tanamanSayurDestroyRow(TanamanPanganSayurRow $row) { return $this->genericDestroy($row); }

    public function tanamanPanganRows() { return $this->genericRows(TanamanPanganKelompokRow::class); }
    public function tanamanPanganStoreRow(Request $r) { return $this->genericStore($r, TanamanPanganKelompokRow::class); }
    public function tanamanPanganUpdateRow(Request $r, TanamanPanganKelompokRow $row) { return $this->genericUpdate($r, $row); }
    public function tanamanPanganDestroyRow(TanamanPanganKelompokRow $row) { return $this->genericDestroy($row); }

    protected function genericRows(string $modelClass)
    {
        $u = Auth::user(); if(!$u) return response()->json([],401);
        $rows = $modelClass::orderBy('created_at','desc')->get()->map(function($r){
            return ['id'=>$r->id,'uraian'=>$r->uraian,'satuan'=>$r->satuan,'values'=>$r->values ?: []];
        });
        return response()->json($rows);
    }
    protected function genericStore(Request $request, string $modelClass)
    {
        $u = Auth::user(); if(!$u) return response()->json(['error'=>'unauthorized'],401);
        if (!in_array($u->role,['super_admin','admin_dinas'])) return response()->json(['error'=>'forbidden'],403);
        $validated = $request->validate([
            'uraian'=>'required|string|max:255',
            'satuan'=>'nullable|string|max:255',
            'values'=>'array'
        ]);
        $row = $modelClass::create([
            'dinas_id'=>$u->dinas_id,
            'uraian'=>$validated['uraian'],
            'satuan'=>$validated['satuan'] ?? null,
            'values'=>$validated['values'] ?? []
        ]);
        return response()->json(['id'=>$row->id,'uraian'=>$row->uraian,'satuan'=>$row->satuan,'values'=>$row->values],201);
    }
    protected function genericUpdate(Request $request, $row)
    {
        $u = Auth::user(); if(!$u) return response()->json(['error'=>'unauthorized'],401);
        if (!in_array($u->role,['super_admin','admin_dinas'])) return response()->json(['error'=>'forbidden'],403);
        if ($u->role==='admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) return response()->json(['error'=>'forbidden'],403);
        $validated = $request->validate([
            'uraian'=>'required|string|max:255',
            'satuan'=>'nullable|string|max:255',
            'values'=>'array'
        ]);
        $row->update([
            'uraian'=>$validated['uraian'],
            'satuan'=>$validated['satuan'] ?? null,
            'values'=>$validated['values'] ?? []
        ]);
        return response()->json(['id'=>$row->id,'uraian'=>$row->uraian,'satuan'=>$row->satuan,'values'=>$row->values]);
    }
    protected function genericDestroy($row)
    {
        $u = \Illuminate\Support\Facades\Auth::user(); if(!$u) return response()->json(['error'=>'unauthorized'],401);
        if ($u->role==='admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) return response()->json(['error'=>'forbidden'],403);
        $row->delete(); return response()->json(['success'=>true]);
    }

}
