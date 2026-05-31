<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use App\Models\DlhRow;
use App\Models\DmRecord;
use App\Models\KetahananPanganRow;
use App\Models\OpdRow;
use App\Models\PariwisataAkomodasiRow;
use App\Models\PariwisataObjekJenisRow;
use App\Models\PariwisataObjekKecamatanRow;
use App\Models\PariwisataPemanduRow;
use App\Models\PariwisataWisatawanRow;
use App\Models\PerikananAlatTangkapRow;
use App\Models\PerikananBinaKelompokRow;
use App\Models\PerikananBudidayaRow;
use App\Models\PerikananInfrastrukturRow;
use App\Models\PerikananProduksiRow;
use App\Models\TanamanPanganKelompokRow;
use App\Models\TanamanPanganSayurRow;
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
        $submissions = class_exists(DataSubmission::class) ? DataSubmission::paginate(15) : null;
        $pending = null;
        if (class_exists(DataSubmission::class)) {
            $q = DataSubmission::with(['dinas', 'user'])->where('status', 'Menunggu Persetujuan');
            $u = Auth::user();
            if ($u && $u->role === 'admin_dinas' && $u->dinas_id) {
                $q->where('dinas_id', $u->dinas_id);
            }
            $pending = $q->orderBy('created_at', 'desc')->limit(20)->get();
        }

        $approvedRecords = null;
        if (class_exists(DmRecord::class)) {
            $approvedRecords = DmRecord::with('dinas')->orderBy('created_at', 'desc')->limit(100)->get();
        }
        $allDinas = \App\Models\Dinas::orderBy('nama_dinas')->get();

        return view('datamanagement', ['submissions' => $submissions, 'pendingSubmissions' => $pending, 'approvedRecords' => $approvedRecords, 'allDinas' => $allDinas]);
    }

    public function createAccount(Request $request)
    {
        $u = Auth::user();
        if (! $u) {
            return redirect()->route('login');
        }
        if ($u->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin_dinas,user',
            'dinas_id' => 'nullable|integer',
        ]);
        if ($validated['role'] === 'admin_dinas' && empty($validated['dinas_id'])) {
            return redirect()->back()->with('error', 'Dinas wajib diisi untuk admin dinas');
        }
        $password = \Illuminate\Support\Str::random(10);
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'role' => $validated['role'],
            'dinas_id' => $validated['dinas_id'] ?? null,
        ]);

        return redirect()->route('datamanagement')->with('success', 'Akun dibuat: '.$user->email);
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
        if (! $u) {
            return redirect()->route('login');
        }

        $dinasId = $validated['dinas_id'] ?? null;
        if (! $dinasId && ! empty($validated['opd'])) {
            $opd = $validated['opd'];
            $d = \App\Models\Dinas::where('nama_dinas', $opd)->first();
            if (! $d) {
                $plain = preg_replace('/^\s*dinas\s+/i', '', (string) $opd);
                $d = \App\Models\Dinas::whereRaw('LOWER(nama_dinas) = ?', [strtolower($plain)])->first();
            }
            if (! $d) {
                $d = \App\Models\Dinas::whereRaw('LOWER(nama_dinas) = ?', [strtolower($opd)])->first();
            }
            if ($d) {
                $dinasId = $d->id;
            }
        }
        if (! $dinasId) {
            $dinasId = $u->dinas_id;
        }

        if (class_exists(DataSubmission::class)) {
            $submission = DataSubmission::create([
                'user_id' => $u->id,
                'dinas_id' => $dinasId,
                'judul_data' => $validated['judul_data'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'file_path' => $validated['file_path'],
                'tahun_perencanaan' => $validated['tahun_perencanaan'],
                'status' => 'Menunggu Persetujuan',
            ]);
            if (class_exists(DmRecord::class)) {
                try {
                    $hasSubmissionId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'submission_id');
                } catch (\Throwable $__) {
                    $hasSubmissionId = true;
                }
                try {
                    $hasDinasId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'dinas_id');
                } catch (\Throwable $___) {
                    $hasDinasId = false;
                }
                try {
                    $hasName = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'name');
                } catch (\Throwable $____) {
                    $hasName = false;
                }
                try {
                    $hasPeriod = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'period');
                } catch (\Throwable $_____) {
                    $hasPeriod = false;
                }
                $exists = false;
                if ($hasSubmissionId) {
                    $exists = DmRecord::where('submission_id', $submission->id)->exists();
                } else {
                    $q = DmRecord::query();
                    if ($hasDinasId) {
                        $q->where('dinas_id', $dinasId);
                    }
                    if ($hasName) {
                        $q->where('name', $validated['judul_data']);
                    }
                    if ($hasPeriod) {
                        $q->where('period', $validated['tahun_perencanaan']);
                    }
                    $exists = $q->exists();
                }
                if (! $exists) {
                    $payload = ['status' => 'Menunggu Persetujuan', 'pic' => $u->name ?? null];
                    if ($hasDinasId) {
                        $payload['dinas_id'] = $dinasId;
                    }
                    if ($hasName) {
                        $payload['name'] = $validated['judul_data'];
                    }
                    if ($hasPeriod) {
                        $payload['period'] = $validated['tahun_perencanaan'];
                    }
                    if ($hasSubmissionId) {
                        $payload['submission_id'] = $submission->id;
                    }
                    DmRecord::create($payload);
                }
            }
        }

        // Untuk fetch AJAX, kembalikan JSON jika diminta
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
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
            'status' => 'required|in:Menunggu Persetujuan,Disetujui,Ditolak',
        ]);

        $submission->update($validated);

        return redirect()->route('datamanagement')->with('success', 'Data updated successfully');
    }

    public function approve(Request $request, DataSubmission $submission)
    {
        $u = Auth::user();
        if (! $u) {
            return redirect()->route('login');
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $submission->dinas_id !== $u->dinas_id) {
            return redirect()->route('datamanagement')->with('error', 'Tidak berhak menyetujui');
        }
        $submission->status = 'Disetujui';
        $submission->save();
        $this->logActivity('approve', $submission->id, 'disetujui', ['judul_data' => $submission->judul_data, 'tahun_perencanaan' => $submission->tahun_perencanaan]);

        if (class_exists(DmRecord::class)) {
            try {
                $hasSubmissionId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'submission_id');
            } catch (\Throwable $__) {
                $hasSubmissionId = true;
            }
            try {
                $hasDinasId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'dinas_id');
            } catch (\Throwable $___) {
                $hasDinasId = false;
            }
            try {
                $hasName = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'name');
            } catch (\Throwable $____) {
                $hasName = false;
            }
            try {
                $hasPeriod = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'period');
            } catch (\Throwable $_____) {
                $hasPeriod = false;
            }

            $exists = false;
            if ($hasSubmissionId) {
                $exists = DmRecord::where('submission_id', $submission->id)->exists();
            } else {
                $q = DmRecord::query();
                if ($hasDinasId) {
                    $q->where('dinas_id', $submission->dinas_id);
                }
                if ($hasName) {
                    $q->where('name', $submission->judul_data);
                }
                if ($hasPeriod) {
                    $q->where('period', $submission->tahun_perencanaan);
                }
                $exists = $q->exists();
            }

            if ($exists) {
                $rec = $hasSubmissionId ? DmRecord::where('submission_id', $submission->id)->first() : DmRecord::where(function ($qq) use ($hasDinasId, $hasName, $hasPeriod, $submission) {
                    if ($hasDinasId) {
                        $qq->where('dinas_id', $submission->dinas_id);
                    }
                    if ($hasName) {
                        $qq->where('name', $submission->judul_data);
                    }
                    if ($hasPeriod) {
                        $qq->where('period', $submission->tahun_perencanaan);
                    }
                })->first();
                if ($rec) {
                    $rec->update(['status' => 'Disetujui', 'pic' => Auth::user()->name ?? null]);
                }
            } else {
                $payload = ['status' => 'Disetujui', 'pic' => Auth::user()->name ?? null];
                if ($hasDinasId) {
                    $payload['dinas_id'] = $submission->dinas_id;
                }
                if ($hasName) {
                    $payload['name'] = $submission->judul_data;
                }
                if ($hasPeriod) {
                    $payload['period'] = $submission->tahun_perencanaan;
                }
                if ($hasSubmissionId) {
                    $payload['submission_id'] = $submission->id;
                }
                DmRecord::create($payload);
            }

            // Auto insert ke tabel Perdagangan per dinas saat ACC
            try {
                $fp = (string) $submission->file_path;
                if (stripos($fp, 'perdagangan_pdrb') !== false && class_exists(\App\Models\PerdaganganPdrbRow::class)) {
                    $valsFromOpd = optional(OpdRow::where('dinas_id', $submission->dinas_id)->where('table_key', 'perdagangan_pdrb')->where('uraian', $submission->judul_data)->first())->values ?: [];
                    if (! \App\Models\PerdaganganPdrbRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerdaganganPdrbRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => $valsFromOpd]);
                    }
                }
                if (stripos($fp, 'perdagangan_ekspor') !== false && class_exists(\App\Models\PerdaganganEksporRow::class)) {
                    $valsFromOpd = optional(OpdRow::where('dinas_id', $submission->dinas_id)->where('table_key', 'perdagangan_eks')->where('uraian', $submission->judul_data)->first())->values ?: [];
                    if (! \App\Models\PerdaganganEksporRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerdaganganEksporRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => $valsFromOpd]);
                    }
                }
                if (stripos($fp, 'koperasi') !== false && class_exists(\App\Models\KoperasiRow::class)) {
                    $valsFromOpd = optional(OpdRow::where('dinas_id', $submission->dinas_id)->where('table_key', 'koperasi_perkembangan')->where('uraian', $submission->judul_data)->first())->values ?: [];
                    if (! \App\Models\KoperasiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\KoperasiRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => $valsFromOpd]);
                    }
                }
                if (stripos($fp, 'dpmptsp') !== false && class_exists(\App\Models\DpmptspInvestasiRow::class)) {
                    $valsFromOpd = optional(OpdRow::where('dinas_id', $submission->dinas_id)->where('table_key', 'dpmptsp_investasi')->where('uraian', $submission->judul_data)->first())->values ?: [];
                    if (! \App\Models\DpmptspInvestasiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\DpmptspInvestasiRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => $valsFromOpd]);
                    }
                }
                if (stripos($fp, 'perindustrian_hb') !== false && class_exists(\App\Models\PerindustrianHbRow::class)) {
                    if (! \App\Models\PerindustrianHbRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerindustrianHbRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perindustrian_hk') !== false && class_exists(\App\Models\PerindustrianHkRow::class)) {
                    if (! \App\Models\PerindustrianHkRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerindustrianHkRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perindustrian_growth') !== false && class_exists(\App\Models\PerindustrianGrowthRow::class)) {
                    if (! \App\Models\PerindustrianGrowthRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerindustrianGrowthRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perkebunan_pop') !== false && class_exists(\App\Models\PerkebunanPopRow::class)) {
                    if (! \App\Models\PerkebunanPopRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerkebunanPopRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perkebunan_prod') !== false && class_exists(\App\Models\PerkebunanProdRow::class)) {
                    if (! \App\Models\PerkebunanProdRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerkebunanProdRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perkebunan_luas') !== false && class_exists(\App\Models\PerkebunanLuasRow::class)) {
                    if (! \App\Models\PerkebunanLuasRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerkebunanLuasRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perikanan_inf') !== false && class_exists(\App\Models\PerikananInfrastrukturRow::class)) {
                    if (! \App\Models\PerikananInfrastrukturRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananInfrastrukturRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perikanan_alt') !== false && class_exists(\App\Models\PerikananAlatTangkapRow::class)) {
                    if (! \App\Models\PerikananAlatTangkapRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananAlatTangkapRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perikanan_bud') !== false && class_exists(\App\Models\PerikananBudidayaRow::class)) {
                    if (! \App\Models\PerikananBudidayaRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananBudidayaRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perikanan_pro') !== false && class_exists(\App\Models\PerikananProduksiRow::class)) {
                    if (! \App\Models\PerikananProduksiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananProduksiRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'perikanan_bin') !== false && class_exists(\App\Models\PerikananBinaKelompokRow::class)) {
                    if (! \App\Models\PerikananBinaKelompokRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PerikananBinaKelompokRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'ketahanan_pangan') !== false && class_exists(\App\Models\KetahananPanganRow::class)) {
                    if (! \App\Models\KetahananPanganRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\KetahananPanganRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'pariwisata_akomodasi') !== false && class_exists(\App\Models\PariwisataAkomodasiRow::class)) {
                    if (! \App\Models\PariwisataAkomodasiRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataAkomodasiRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'pariwisata_wisatawan') !== false && class_exists(\App\Models\PariwisataWisatawanRow::class)) {
                    if (! \App\Models\PariwisataWisatawanRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataWisatawanRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'pariwisata_jenis') !== false && class_exists(\App\Models\PariwisataObjekJenisRow::class)) {
                    if (! \App\Models\PariwisataObjekJenisRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataObjekJenisRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'pariwisata_kecamatan') !== false && class_exists(\App\Models\PariwisataObjekKecamatanRow::class)) {
                    if (! \App\Models\PariwisataObjekKecamatanRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataObjekKecamatanRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'pariwisata_pemandu') !== false && class_exists(\App\Models\PariwisataPemanduRow::class)) {
                    if (! \App\Models\PariwisataPemanduRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\PariwisataPemanduRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
                if (stripos($fp, 'dlh_inline') !== false && class_exists(\App\Models\DlhRow::class)) {
                    if (! \App\Models\DlhRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\DlhRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'satuan' => null, 'y2019' => null, 'y2020' => null, 'y2021' => null, 'y2022' => null, 'y2023' => null]);
                    }
                }
                if (stripos($fp, 'bapenda_pad') !== false && class_exists(\App\Models\BapendaPadRow::class)) {
                    if (! \App\Models\BapendaPadRow::where('dinas_id', $submission->dinas_id)->where('uraian', $submission->judul_data)->exists()) {
                        \App\Models\BapendaPadRow::create(['dinas_id' => $submission->dinas_id, 'uraian' => $submission->judul_data, 'values' => []]);
                    }
                }
            } catch (\Throwable $__) {
            }
        }

        return redirect()->route('datamanagement')->with('success', 'Pengajuan disetujui');
    }

    public function records(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json([], 401);
        }
        $q = DmRecord::with('dinas');
        if ($u->role === 'admin_dinas' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id);
        }
        $status = $request->query('status');
        if ($status) {
            $q->where('status', $status);
        }
        $opd = $request->query('opd');
        if ($opd) {
            $q->whereHas('dinas', function ($qq) use ($opd) {
                $qq->where('nama_dinas', $opd);
            });
        }
        $rows = $q->orderBy('created_at', 'desc')->get()->map(function ($r) {
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

    public function destroyRecord(DmRecord $record)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $record->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $record->delete();
        $this->logActivity('delete', $record->id, null, ['name' => $record->name, 'period' => $record->period]);

        return response()->json(['success' => true]);
    }

    public function storeRecord(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $validated = $request->validate([
            'opd' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'period' => 'nullable|string|max:255',
            'status' => 'required|in:Menunggu Persetujuan,Disetujui,Ditolak',
            'pic' => 'nullable|string|max:255',
        ]);

        $dinas = \App\Models\Dinas::where('nama_dinas', $validated['opd'])->first();
        if (! $dinas) {
            return response()->json(['error' => 'opd_not_found'], 422);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $rec = DmRecord::create([
            'dinas_id' => $dinas->id,
            'name' => $validated['name'],
            'period' => $validated['period'] ?? null,
            'status' => $validated['status'],
            'pic' => $validated['pic'] ?? ($u->name ?? null),
        ]);
        $this->logActivity('create', $rec->id, null, ['name' => $rec->name, 'period' => $rec->period]);

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
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $validated = $request->validate([
            'opd' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'period' => 'nullable|string|max:255',
            'status' => 'required|in:Menunggu Persetujuan,Disetujui,Ditolak',
            'pic' => 'nullable|string|max:255',
        ]);

        $dinas = \App\Models\Dinas::where('nama_dinas', $validated['opd'])->first();
        if (! $dinas) {
            return response()->json(['error' => 'opd_not_found'], 422);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $record->update([
            'dinas_id' => $dinas->id,
            'name' => $validated['name'],
            'period' => $validated['period'] ?? null,
            'status' => $validated['status'],
            'pic' => $validated['pic'] ?? ($u->name ?? null),
        ]);
        $this->logActivity('update', $record->id, null, ['name' => $record->name, 'period' => $record->period, 'status' => $record->status]);

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

    public function reject(Request $request, DataSubmission $submission)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return redirect()->route('login');
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $submission->dinas_id !== $u->dinas_id) {
            return redirect()->route('datamanagement')->with('error', 'Tidak berhak menolak');
        }
        $submission->status = 'Ditolak';
        $submission->catatan_revisi = $request->input('catatan_revisi');
        $submission->save();
        $this->logActivity('reject', $submission->id, 'ditolak', ['judul_data' => $submission->judul_data, 'catatan' => $submission->catatan_revisi]);
        if (class_exists(DmRecord::class)) {
            try {
                $hasSubmissionId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'submission_id');
            } catch (\Throwable $__) {
                $hasSubmissionId = true;
            }
            try {
                $hasDinasId = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'dinas_id');
            } catch (\Throwable $___) {
                $hasDinasId = false;
            }
            try {
                $hasName = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'name');
            } catch (\Throwable $____) {
                $hasName = false;
            }
            try {
                $hasPeriod = \Illuminate\Support\Facades\Schema::hasColumn('dm_records', 'period');
            } catch (\Throwable $_____) {
                $hasPeriod = false;
            }
            $rec = $hasSubmissionId ? DmRecord::where('submission_id', $submission->id)->first() : DmRecord::where(function ($qq) use ($hasDinasId, $hasName, $hasPeriod, $submission) {
                if ($hasDinasId) {
                    $qq->where('dinas_id', $submission->dinas_id);
                }
                if ($hasName) {
                    $qq->where('name', $submission->judul_data);
                }
                if ($hasPeriod) {
                    $qq->where('period', $submission->tahun_perencanaan);
                }
            })->first();
            if ($rec) {
                $rec->update(['status' => 'Ditolak', 'pic' => $u->name ?? null]);
            }
        }

        return redirect()->route('datamanagement')->with('success', 'Pengajuan ditolak');
    }

    public function showSubmission(Request $request, DataSubmission $submission)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $submission->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $opdName = optional($submission->dinas)->nama_dinas;
        $fields = [];
        $fp = (string) $submission->file_path;
        $key = null;
        if (stripos($fp, 'perdagangan_pdrb') !== false) {
            $key = 'perdagangan_pdrb';
        } elseif (stripos($fp, 'perdagangan_ekspor') !== false) {
            $key = 'perdagangan_eks';
        } elseif (stripos($fp, 'koperasi') !== false) {
            $key = 'koperasi_perkembangan';
        } elseif (stripos($fp, 'dpmptsp') !== false) {
            $key = 'dpmptsp_investasi';
        } elseif (stripos($fp, 'perindustrian_hb') !== false) {
            $key = 'perindustrian_hb';
        } elseif (stripos($fp, 'perindustrian_hk') !== false) {
            $key = 'perindustrian_hk';
        } elseif (stripos($fp, 'perindustrian_growth') !== false) {
            $key = 'perindustrian_gr';
        } elseif (stripos($fp, 'perkebunan_pop') !== false) {
            $key = 'perkebunan_pop';
        } elseif (stripos($fp, 'perkebunan_prod') !== false) {
            $key = 'perkebunan_prod';
        } elseif (stripos($fp, 'perkebunan_luas') !== false) {
            $key = 'perkebunan_luas';
        } elseif (stripos($fp, 'perikanan_inf') !== false) {
            $key = 'perikanan_inf';
        } elseif (stripos($fp, 'perikanan_alt') !== false) {
            $key = 'perikanan_alt';
        } elseif (stripos($fp, 'perikanan_bud') !== false) {
            $key = 'perikanan_bud';
        } elseif (stripos($fp, 'perikanan_pro') !== false) {
            $key = 'perikanan_pro';
        } elseif (stripos($fp, 'perikanan_bin') !== false) {
            $key = 'perikanan_bin';
        } elseif (stripos($fp, 'ketahanan_pangan') !== false) {
            $key = 'ketahanan_pangan';
        } elseif (stripos($fp, 'pariwisata_akomodasi') !== false) {
            $key = 'pariwisata_akomodasi';
        } elseif (stripos($fp, 'pariwisata_wisatawan') !== false) {
            $key = 'pariwisata_wisatawan';
        } elseif (stripos($fp, 'pariwisata_jenis') !== false) {
            $key = 'pariwisata_jenis';
        } elseif (stripos($fp, 'pariwisata_kecamatan') !== false) {
            $key = 'pariwisata_kecamatan';
        } elseif (stripos($fp, 'pariwisata_pemandu') !== false) {
            $key = 'pariwisata_pemandu';
        } elseif (stripos($fp, 'dlh_inline') !== false) {
            $key = 'dlh_inline';
        } elseif (stripos($fp, 'bapenda_pad') !== false) {
            $key = 'bapenda_pad';
        }

        if ($key) {
            $row = OpdRow::where('dinas_id', $submission->dinas_id)
                ->where('table_key', $key)
                ->where('uraian', $submission->judul_data)
                ->first();
            if ($row) {
                $fields = $row->values ?: [];
            }
        }

        $history = DataSubmission::with(['dinas', 'user'])
            ->where('dinas_id', $submission->dinas_id)
            ->where('judul_data', $submission->judul_data)
            ->where('id', '<>', $submission->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'created_at' => optional($s->created_at)->format('d/m/Y'),
                    'opd' => optional($s->dinas)->nama_dinas,
                    'status' => $s->status,
                ];
            });

        return response()->json([
            'id' => $submission->id,
            'judul_data' => $submission->judul_data,
            'created_at' => optional($submission->created_at)->format('d/m/Y'),
            'opd' => $opdName,
            'status' => $submission->status,
            'file_path' => $submission->file_path,
            'tahun_perencanaan' => $submission->tahun_perencanaan,
            'fields' => $fields,
            'history' => $history,
            'user' => optional($submission->user)->name,
        ]);
    }

    public function dlhRows(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json([], 401);
        }
        $dlh = \App\Models\Dinas::where('nama_dinas', 'DLH')->first() ?: \App\Models\Dinas::where('nama_dinas', 'Dinas Lingkungan Hidup')->first();
        if (! $dlh) {
            return response()->json([]);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dlh->id) {
            return response()->json([], 403);
        }
        $rows = DlhRow::where('dinas_id', $dlh->id)->orderBy('created_at', 'desc')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'no' => null,
                'uraian' => $r->uraian,
                'satuan' => $r->satuan,
                'y2025' => $r->y2025,
                'y2026' => $r->y2026,
                'y2027' => $r->y2027,
                'y2028' => $r->y2028,
                'y2029' => $r->y2029,
            ];
        });

        return response()->json($rows);
    }

    public function dlhStoreRow(Request $request)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $dlh = \App\Models\Dinas::where('nama_dinas', 'DLH')->first() ?: \App\Models\Dinas::where('nama_dinas', 'Dinas Lingkungan Hidup')->first();
        if (! $dlh) {
            return response()->json(['error' => 'dlh_not_found'], 404);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dlh->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'y2025' => 'nullable|string|max:255',
            'y2026' => 'nullable|string|max:255',
            'y2027' => 'nullable|string|max:255',
            'y2028' => 'nullable|string|max:255',
            'y2029' => 'nullable|string|max:255',
        ]);

        $row = DlhRow::create(array_merge($validated, ['dinas_id' => $dlh->id]));

        return response()->json(['id' => $row->id] + $validated, 201);
    }

    public function dlhUpdateRow(Request $request, DlhRow $row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'y2025' => 'nullable|string|max:255',
            'y2026' => 'nullable|string|max:255',
            'y2027' => 'nullable|string|max:255',
            'y2028' => 'nullable|string|max:255',
            'y2029' => 'nullable|string|max:255',
        ]);

        $row->update($validated);

        return response()->json(['id' => $row->id] + $validated);
    }

    public function dlhDestroyRow(DlhRow $row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $row->delete();

        return response()->json(['success' => true]);
    }

    public function opdRows(Request $request)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json([], 401);
        }
        $opd = $request->query('opd');
        $key = $request->query('key');
        if (! $opd || ! $key) {
            return response()->json([]);
        }
        $dinas = \App\Models\Dinas::where('nama_dinas', $opd)->first();
        if (! $dinas) {
            return response()->json([]);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json([], 403);
        }
        $rows = OpdRow::where('dinas_id', $dinas->id)->where('table_key', $key)->orderBy('created_at', 'desc')->get()->map(function ($r) {
            return ['id' => $r->id, 'uraian' => $r->uraian, 'satuan' => $r->satuan, 'values' => $r->values ?: []];
        });

        return response()->json($rows);
    }

    public function opdStoreRow(Request $request)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas', 'user'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate([
            'opd' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array',
        ]);
        $dinas = \App\Models\Dinas::where('nama_dinas', $validated['opd'])->first();
        if (! $dinas) {
            return response()->json(['error' => 'opd_not_found'], 422);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'user' && $u->dinas_id && $u->dinas_id !== $dinas->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        // Redirect ke tabel khusus per dinas bila key dikenali
        if ($validated['key'] === 'koperasi_perkembangan' && class_exists(\App\Models\KoperasiRow::class)) {
            $row = \App\Models\KoperasiRow::create(['dinas_id' => $dinas->id, 'uraian' => $validated['uraian'], 'satuan' => $validated['satuan'] ?? null, 'values' => $validated['values'] ?? []]);

            return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values], 201);
        }
        if ($validated['key'] === 'dpmptsp_investasi' && class_exists(\App\Models\DpmptspInvestasiRow::class)) {
            $row = \App\Models\DpmptspInvestasiRow::create(['dinas_id' => $dinas->id, 'uraian' => $validated['uraian'], 'satuan' => $validated['satuan'] ?? null, 'values' => $validated['values'] ?? []]);

            return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values], 201);
        }
        $row = OpdRow::create([
            'dinas_id' => $dinas->id,
            'table_key' => $validated['key'],
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $validated['values'] ?? [],
        ]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values], 201);
    }

    public function opdUpdateRow(Request $request, OpdRow $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array',
        ]);
        $row->update([
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $validated['values'] ?? [],
        ]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values]);
    }

    public function opdDestroyRow(OpdRow $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $row->delete();

        return response()->json(['success' => true]);
    }

    public function perdaganganPdrbRows()
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json([], 401);
        }
        $rows = \App\Models\PerdaganganPdrbRow::orderBy('created_at', 'desc')->get()->map(function ($r) {
            return ['id' => $r->id, 'uraian' => $r->uraian, 'satuan' => $r->satuan, 'values' => $r->values ?: []];
        });

        return response()->json($rows);
    }

    public function perdaganganPdrbStoreRow(Request $request)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate(['uraian' => 'required|string|max:255', 'satuan' => 'nullable|string|max:255', 'values' => 'array']);
        $row = \App\Models\PerdaganganPdrbRow::create(['dinas_id' => $u->dinas_id, 'uraian' => $validated['uraian'], 'satuan' => $validated['satuan'] ?? null, 'values' => $validated['values'] ?? []]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values], 201);
    }

    public function perdaganganPdrbUpdateRow(Request $request, \App\Models\PerdaganganPdrbRow $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate(['uraian' => 'required|string|max:255', 'satuan' => 'nullable|string|max:255', 'values' => 'array']);
        $row->update(['uraian' => $validated['uraian'], 'satuan' => $validated['satuan'] ?? null, 'values' => $validated['values'] ?? []]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values]);
    }

    public function perdaganganPdrbDestroyRow(\App\Models\PerdaganganPdrbRow $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $row->delete();

        return response()->json(['success' => true]);
    }

    public function perdaganganEksRows()
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json([], 401);
        }
        $rows = \App\Models\PerdaganganEksporRow::orderBy('created_at', 'desc')->get()->map(function ($r) {
            return ['id' => $r->id, 'uraian' => $r->uraian, 'satuan' => $r->satuan, 'values' => $r->values ?: []];
        });

        return response()->json($rows);
    }

    public function perdaganganEksStoreRow(Request $request)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate(['uraian' => 'required|string|max:255', 'satuan' => 'nullable|string|max:255', 'values' => 'array']);
        $row = \App\Models\PerdaganganEksporRow::create(['dinas_id' => $u->dinas_id, 'uraian' => $validated['uraian'], 'satuan' => $validated['satuan'] ?? null, 'values' => $validated['values'] ?? []]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values], 201);
    }

    public function perdaganganEksUpdateRow(Request $request, \App\Models\PerdaganganEksporRow $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate(['uraian' => 'required|string|max:255', 'satuan' => 'nullable|string|max:255', 'values' => 'array']);
        $row->update(['uraian' => $validated['uraian'], 'satuan' => $validated['satuan'] ?? null, 'values' => $validated['values'] ?? []]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values]);
    }

    public function perdaganganEksDestroyRow(\App\Models\PerdaganganEksporRow $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $row->delete();

        return response()->json(['success' => true]);
    }

    public function ketapangRows()
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json([], 401);
        }
        $rows = KetahananPanganRow::orderBy('created_at', 'desc')->get()->map(function ($r) {
            return ['id' => $r->id, 'uraian' => $r->uraian, 'satuan' => $r->satuan, 'values' => $r->values ?: []];
        });

        return response()->json($rows);
    }

    public function ketapangStoreRow(Request $request)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array',
        ]);
        $row = KetahananPanganRow::create([
            'dinas_id' => $u->dinas_id,
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $validated['values'] ?? [],
        ]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values], 201);
    }

    public function ketapangUpdateRow(Request $request, KetahananPanganRow $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array',
        ]);
        $row->update([
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $validated['values'] ?? [],
        ]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values]);
    }

    public function ketapangDestroyRow(KetahananPanganRow $row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $row->delete();

        return response()->json(['success' => true]);
    }

    public function perikananInfRows()
    {
        return $this->genericRows(PerikananInfrastrukturRow::class);
    }

    public function perikananInfStoreRow(Request $r)
    {
        return $this->genericStore($r, PerikananInfrastrukturRow::class);
    }

    public function perikananInfUpdateRow(Request $r, PerikananInfrastrukturRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perikananInfDestroyRow(PerikananInfrastrukturRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perikananAltRows()
    {
        return $this->genericRows(PerikananAlatTangkapRow::class);
    }

    public function perikananAltStoreRow(Request $r)
    {
        return $this->genericStore($r, PerikananAlatTangkapRow::class);
    }

    public function perikananAltUpdateRow(Request $r, PerikananAlatTangkapRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perikananAltDestroyRow(PerikananAlatTangkapRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perikananBudRows()
    {
        return $this->genericRows(PerikananBudidayaRow::class);
    }

    public function perikananBudStoreRow(Request $r)
    {
        return $this->genericStore($r, PerikananBudidayaRow::class);
    }

    public function perikananBudUpdateRow(Request $r, PerikananBudidayaRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perikananBudDestroyRow(PerikananBudidayaRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perikananProRows()
    {
        return $this->genericRows(PerikananProduksiRow::class);
    }

    public function perikananProStoreRow(Request $r)
    {
        return $this->genericStore($r, PerikananProduksiRow::class);
    }

    public function perikananProUpdateRow(Request $r, PerikananProduksiRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perikananProDestroyRow(PerikananProduksiRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perikananBinRows()
    {
        return $this->genericRows(PerikananBinaKelompokRow::class);
    }

    public function perikananBinStoreRow(Request $r)
    {
        return $this->genericStore($r, PerikananBinaKelompokRow::class);
    }

    public function perikananBinUpdateRow(Request $r, PerikananBinaKelompokRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perikananBinDestroyRow(PerikananBinaKelompokRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function pariwisataAkoRows()
    {
        return $this->genericRows(PariwisataAkomodasiRow::class);
    }

    public function pariwisataAkoStoreRow(Request $r)
    {
        return $this->genericStore($r, PariwisataAkomodasiRow::class);
    }

    public function pariwisataAkoUpdateRow(Request $r, PariwisataAkomodasiRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function pariwisataAkoDestroyRow(PariwisataAkomodasiRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function pariwisataWisRows()
    {
        return $this->genericRows(PariwisataWisatawanRow::class);
    }

    public function pariwisataWisStoreRow(Request $r)
    {
        return $this->genericStore($r, PariwisataWisatawanRow::class);
    }

    public function pariwisataWisUpdateRow(Request $r, PariwisataWisatawanRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function pariwisataWisDestroyRow(PariwisataWisatawanRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function pariwisataJenRows()
    {
        return $this->genericRows(PariwisataObjekJenisRow::class);
    }

    public function pariwisataJenStoreRow(Request $r)
    {
        return $this->genericStore($r, PariwisataObjekJenisRow::class);
    }

    public function pariwisataJenUpdateRow(Request $r, PariwisataObjekJenisRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function pariwisataJenDestroyRow(PariwisataObjekJenisRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function pariwisataKecRows()
    {
        return $this->genericRows(PariwisataObjekKecamatanRow::class);
    }

    public function pariwisataKecStoreRow(Request $r)
    {
        return $this->genericStore($r, PariwisataObjekKecamatanRow::class);
    }

    public function pariwisataKecUpdateRow(Request $r, PariwisataObjekKecamatanRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function pariwisataKecDestroyRow(PariwisataObjekKecamatanRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function pariwisataPemRows()
    {
        return $this->genericRows(PariwisataPemanduRow::class);
    }

    public function pariwisataPemStoreRow(Request $r)
    {
        return $this->genericStore($r, PariwisataPemanduRow::class);
    }

    public function pariwisataPemUpdateRow(Request $r, PariwisataPemanduRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function pariwisataPemDestroyRow(PariwisataPemanduRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function tanamanSayurRows()
    {
        return $this->genericRows(TanamanPanganSayurRow::class);
    }

    public function tanamanSayurStoreRow(Request $r)
    {
        return $this->genericStore($r, TanamanPanganSayurRow::class);
    }

    public function tanamanSayurUpdateRow(Request $r, TanamanPanganSayurRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function tanamanSayurDestroyRow(TanamanPanganSayurRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function tanamanPanganRows()
    {
        return $this->genericRows(TanamanPanganKelompokRow::class);
    }

    public function tanamanPanganStoreRow(Request $r)
    {
        return $this->genericStore($r, TanamanPanganKelompokRow::class);
    }

    public function tanamanPanganUpdateRow(Request $r, TanamanPanganKelompokRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function tanamanPanganDestroyRow(TanamanPanganKelompokRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perkebunanPopRows()
    {
        return $this->genericRows(\App\Models\PerkebunanPopRow::class);
    }

    public function perkebunanPopStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\PerkebunanPopRow::class);
    }

    public function perkebunanPopUpdateRow(Request $r, \App\Models\PerkebunanPopRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perkebunanPopDestroyRow(\App\Models\PerkebunanPopRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perkebunanProdRows()
    {
        return $this->genericRows(\App\Models\PerkebunanProdRow::class);
    }

    public function perkebunanProdStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\PerkebunanProdRow::class);
    }

    public function perkebunanProdUpdateRow(Request $r, \App\Models\PerkebunanProdRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perkebunanProdDestroyRow(\App\Models\PerkebunanProdRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perkebunanLuasRows()
    {
        return $this->genericRows(\App\Models\PerkebunanLuasRow::class);
    }

    public function perkebunanLuasStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\PerkebunanLuasRow::class);
    }

    public function perkebunanLuasUpdateRow(Request $r, \App\Models\PerkebunanLuasRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perkebunanLuasDestroyRow(\App\Models\PerkebunanLuasRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function bapendaPadRows()
    {
        return $this->genericRows(\App\Models\BapendaPadRow::class);
    }

    public function bapendaPadStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\BapendaPadRow::class);
    }

    public function bapendaPadUpdateRow(Request $r, \App\Models\BapendaPadRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function bapendaPadDestroyRow(\App\Models\BapendaPadRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function koperasiRows()
    {
        return $this->genericRows(\App\Models\KoperasiRow::class);
    }

    public function koperasiStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\KoperasiRow::class);
    }

    public function koperasiUpdateRow(Request $r, \App\Models\KoperasiRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function koperasiDestroyRow(\App\Models\KoperasiRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function dpmptspRows()
    {
        return $this->genericRows(\App\Models\DpmptspInvestasiRow::class);
    }

    public function dpmptspStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\DpmptspInvestasiRow::class);
    }

    public function dpmptspUpdateRow(Request $r, \App\Models\DpmptspInvestasiRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function dpmptspDestroyRow(\App\Models\DpmptspInvestasiRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perindustrianHbRows()
    {
        return $this->genericRows(\App\Models\PerindustrianHbRow::class);
    }

    public function perindustrianHbStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\PerindustrianHbRow::class);
    }

    public function perindustrianHbUpdateRow(Request $r, \App\Models\PerindustrianHbRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perindustrianHbDestroyRow(\App\Models\PerindustrianHbRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perindustrianHkRows()
    {
        return $this->genericRows(\App\Models\PerindustrianHkRow::class);
    }

    public function perindustrianHkStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\PerindustrianHkRow::class);
    }

    public function perindustrianHkUpdateRow(Request $r, \App\Models\PerindustrianHkRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perindustrianHkDestroyRow(\App\Models\PerindustrianHkRow $row)
    {
        return $this->genericDestroy($row);
    }

    public function perindustrianGrRows()
    {
        return $this->genericRows(\App\Models\PerindustrianGrowthRow::class);
    }

    public function perindustrianGrStoreRow(Request $r)
    {
        return $this->genericStore($r, \App\Models\PerindustrianGrowthRow::class);
    }

    public function perindustrianGrUpdateRow(Request $r, \App\Models\PerindustrianGrowthRow $row)
    {
        return $this->genericUpdate($r, $row);
    }

    public function perindustrianGrDestroyRow(\App\Models\PerindustrianGrowthRow $row)
    {
        return $this->genericDestroy($row);
    }

    protected function genericRows(string $modelClass)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json([], 401);
        }
        $rows = $modelClass::orderBy('created_at', 'desc')->get()->map(function ($r) {
            return ['id' => $r->id, 'uraian' => $r->uraian, 'satuan' => $r->satuan, 'values' => $r->values ?: []];
        });

        return response()->json($rows);
    }

    protected function genericStore(Request $request, string $modelClass)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array',
        ]);
        $vals = $this->normalizeValuesForModel($modelClass, $validated['values'] ?? []);
        $payload = [
            'dinas_id' => $u->dinas_id,
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $vals,
        ];
        $payload = array_merge($payload, $this->extractYearColumns($modelClass, $vals));
        $row = $modelClass::create($payload);
        $this->logActivity('create', $row->id, null, ['uraian' => $row->uraian, 'entity' => $modelClass]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values], 201);
    }

    protected function genericUpdate(Request $request, $row)
    {
        $u = Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'values' => 'array',
        ]);
        $vals = $this->normalizeValuesForModel(get_class($row), $validated['values'] ?? []);
        $payload = [
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'] ?? null,
            'values' => $vals,
        ];
        $payload = array_merge($payload, $this->extractYearColumns(get_class($row), $vals));
        $row->update($payload);
        $this->logActivity('update', $row->id, null, ['uraian' => $row->uraian, 'entity' => get_class($row)]);

        return response()->json(['id' => $row->id, 'uraian' => $row->uraian, 'satuan' => $row->satuan, 'values' => $row->values]);
    }

    protected function genericDestroy($row)
    {
        $u = \Illuminate\Support\Facades\Auth::user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin', 'admin_dinas'])) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id && $row->dinas_id !== $u->dinas_id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $row->delete();
        $this->logActivity('delete', $row->id, null, ['entity' => get_class($row)]);

        return response()->json(['success' => true]);
    }

    protected function normalizeValuesForModel(string $modelClass, array $values): array
    {
        $keys = [];
        if ($modelClass === \App\Models\PerdaganganPdrbRow::class) {
            $keys = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        } elseif ($modelClass === \App\Models\PerdaganganEksporRow::class) {
            $keys = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        } elseif ($modelClass === \App\Models\DpmptspInvestasiRow::class) {
            $keys = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        } elseif ($modelClass === \App\Models\BapendaPadRow::class) {
            $keys = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        } elseif ($modelClass === \App\Models\KoperasiRow::class
            || $modelClass === \App\Models\PerindustrianHbRow::class
            || $modelClass === \App\Models\PerindustrianHkRow::class
            || $modelClass === \App\Models\PerindustrianGrowthRow::class
            || $modelClass === \App\Models\PerkebunanPopRow::class
            || $modelClass === \App\Models\PerkebunanProdRow::class
            || $modelClass === \App\Models\PerkebunanLuasRow::class
            || $modelClass === \App\Models\DlhRow::class) {
            $keys = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        } else {
            $keys = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        }
        $norm = [];
        foreach ($keys as $k) {
            $norm[$k] = array_key_exists($k, $values) ? (is_string($values[$k]) ? trim($values[$k]) : $values[$k]) : null;
        }
        foreach ($values as $k => $v) {
            if (! array_key_exists($k, $norm)) {
                $norm[$k] = $v;
            }
        }

        return $norm;
    }

    protected function extractYearColumns(string $modelClass, array $vals): array
    {
        $map = [];
        $years = [];
        if ($modelClass === \App\Models\PerdaganganPdrbRow::class || $modelClass === \App\Models\DpmptspInvestasiRow::class || $modelClass === \App\Models\BapendaPadRow::class) {
            $years = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        } else {
            $years = ['y2025', 'y2026', 'y2027', 'y2028', 'y2029'];
        }
        try {
            $model = new $modelClass;
            $table = method_exists($model, 'getTable') ? $model->getTable() : null;
        } catch (\Throwable $__) {
            $table = null;
        }
        foreach ($years as $k) {
            if ($table && \Illuminate\Support\Facades\Schema::hasColumn($table, $k)) {
                $map[$k] = $vals[$k] ?? null;
            }
        }

        return $map;
    }

    protected function logActivity(string $action, ?int $recordId = null, ?string $approvalStatus = null, array $metadata = [])
    {
        try {
            $u = \Illuminate\Support\Facades\Auth::user();
            if (! $u) {
                return;
            }
            $dinasId = $u->dinas_id ?? null;
            $entity = $metadata['entity'] ?? null;
            \App\Models\ActivityLog::create([
                'user_id' => $u->id,
                'role' => $u->role,
                'dinas_id' => $dinasId,
                'action' => $action,
                'entity' => $entity,
                'record_id' => $recordId,
                'approval_status' => $approvalStatus,
                'metadata' => $metadata,
            ]);
        } catch (\Throwable $__) {
        }
    }
}
