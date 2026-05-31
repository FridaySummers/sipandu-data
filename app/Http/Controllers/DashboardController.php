<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\DataSubmission;
use App\Models\Dinas;
use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalDinas = Dinas::count();
        $totalDataSubmissions = DataSubmission::count();
        $pendingData = DataSubmission::where('status', 'pending')->count();
        $approvedData = DataSubmission::where('status', 'approved')->count();

        // Persentase kenaikan jumlah data: dibandingkan bulan berjalan vs bulan sebelumnya
        $now = now();
        $currMonth = DataSubmission::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $prevMonthDate = $now->copy()->subMonth();
        $prevMonth = DataSubmission::whereYear('created_at', $prevMonthDate->year)
            ->whereMonth('created_at', $prevMonthDate->month)
            ->count();
        $increasePercent = $prevMonth > 0 ? (($currMonth - $prevMonth) / $prevMonth) * 100 : 0;

        return view('dashboard', compact('totalDinas', 'totalDataSubmissions', 'pendingData', 'approvedData', 'increasePercent'));
    }

    public function reports()
    {
        return view('reports');
    }

    public function calendar()
    {
        return view('calendar');
    }

    public function forum()
    {
        return view('forum');
    }

    public function forumThreadView(\App\Models\ForumThread $thread)
    {
        $u = request()->user();
        if (! $u) {
            return redirect()->route('login');
        }
        $key = 'forum_viewed_'.$thread->id;
        if (! session()->has($key)) {
            try {
                $thread->increment('views');
            } catch (\Throwable $e) {
            }
            session()->put($key, true);
        }

        return view('forum-detail', ['thread' => $thread]);
    }

    public function settings()
    {
        return view('settings');
    }

    public function settingsUsersPage(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return redirect()->route('login');
        }
        if ($u->role !== 'super_admin') {
            return redirect()->route('settings');
        }

        return view('settings-users');
    }

    public function settingsUsers(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        if ($u->role !== 'super_admin') {
            return response()->json([], 403);
        }
        $q = trim((string) $request->query('q', ''));
        $users = \App\Models\User::with('dinas')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(500)
            ->get()
            ->map(function ($uu) {
                $d = optional($uu->dinas);

                return [
                    'id' => $uu->id,
                    'name' => $uu->name,
                    'email' => $uu->email,
                    'role' => $uu->role,
                    'position' => $uu->position,
                    'dinas_id' => $uu->dinas_id,
                    'dinas_nama' => $d?->nama_dinas,
                    'dinas_kode' => $d?->kode_dinas,
                    'created_at' => optional($uu->created_at)->toDateTimeString(),
                ];
            });

        return response()->json($users);
    }

    public function settingsUserStore(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role !== 'super_admin') {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $connName = config('database.default');
        $dbName = config('database.connections.'.$connName.'.database');
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DB connection failed for user store', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'db_connection_failed'], 500);
        }
        try {
            $hasUsers = \Illuminate\Support\Facades\Schema::hasTable('users');
        } catch (\Throwable $e) {
            $hasUsers = true;
        }
        if (! $hasUsers) {
            \Illuminate\Support\Facades\Log::error('DB missing table users');

            return response()->json(['error' => 'missing_table_users'], 500);
        }
        if (strtolower((string) $dbName) !== 'sipandudata') {
            \Illuminate\Support\Facades\Log::warning('Unexpected database for user store', ['expected' => 'sipandudata', 'actual' => $dbName]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:super_admin,admin_dinas,user',
            'position' => 'nullable|string|max:255',
            'dinas_id' => 'nullable|integer',
            'password' => 'nullable|string|min:6',
            'opd' => 'nullable|string|max:255',
        ]);
        $did = $validated['dinas_id'] ?? null;
        if (! $did && ! empty($validated['opd'])) {
            $d = Dinas::where('nama_dinas', $validated['opd'])->first();
            if ($d) {
                $did = $d->id;
            }
        }
        if (in_array($validated['role'], ['admin_dinas', 'user']) && empty($did)) {
            return response()->json(['error' => 'dinas_required'], 422);
        }
        $pwd = $validated['password'] ?? \Illuminate\Support\Str::random(10);
        try {
            $pos = $validated['position'] ?? null;
            if (! $pos) {
                $pos = $validated['role'] === 'user' ? 'Staf Dinas' : ($validated['role'] === 'admin_dinas' ? 'Admin Dinas' : ($validated['role'] === 'super_admin' ? 'Super Admin' : null));
            }
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($pwd),
                'role' => $validated['role'],
                'position' => $pos,
                'dinas_id' => $did ?? null,
            ]);
            $this->logActivity('user_create', $user->id, null, ['entity' => \App\Models\User::class, 'email' => $user->email, 'role' => $user->role, 'dinas_id' => $user->dinas_id]);

            return response()->json(['id' => $user->id], 201);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed creating user', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'db_write_failed'], 500);
        }
    }

    public function settingsUserUpdate(Request $request, \App\Models\User $user)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role !== 'super_admin') {
            return response()->json(['error' => 'forbidden'], 403);
        }
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DB connection failed for user update', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'db_connection_failed'], 500);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.($user->id),
            'role' => 'required|in:super_admin,admin_dinas,user',
            'position' => 'nullable|string|max:255',
            'dinas_id' => 'nullable|integer',
            'password' => 'nullable|string|min:6',
            'opd' => 'nullable|string|max:255',
        ]);
        $did = $validated['dinas_id'] ?? null;
        if (! $did && ! empty($validated['opd'])) {
            $d = Dinas::where('nama_dinas', $validated['opd'])->first();
            if ($d) {
                $did = $d->id;
            }
        }
        if (in_array($validated['role'], ['admin_dinas', 'user']) && empty($did)) {
            return response()->json(['error' => 'dinas_required'], 422);
        }
        $pos = $validated['position'] ?? null;
        if (! $pos) {
            $pos = $validated['role'] === 'user' ? 'Staf Dinas' : ($validated['role'] === 'admin_dinas' ? 'Admin Dinas' : ($validated['role'] === 'super_admin' ? 'Super Admin' : null));
        }
        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'position' => $pos,
            'dinas_id' => $did ?? null,
        ];
        if (! empty($validated['password'])) {
            $payload['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        try {
            $user->update($payload);
            $this->logActivity('user_update', $user->id, null, ['entity' => \App\Models\User::class, 'email' => $user->email, 'role' => $user->role, 'dinas_id' => $user->dinas_id]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed updating user', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'db_write_failed'], 500);
        }
    }

    public function settingsUserDestroy(Request $request, \App\Models\User $user)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role !== 'super_admin') {
            return response()->json(['error' => 'forbidden'], 403);
        }
        if ($user->id === $u->id) {
            return response()->json(['error' => 'cannot_delete_self'], 422);
        }
        try {
            $userId = $user->id;
            $userEmail = $user->email;
            $userRole = $user->role;
            $userDinas = $user->dinas_id;
            $user->delete();
            $this->logActivity('user_delete', $userId, null, ['entity' => \App\Models\User::class, 'email' => $userEmail, 'role' => $userRole, 'dinas_id' => $userDinas]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed deleting user', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'db_write_failed'], 500);
        }
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ]);
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $file = $request->file('photo');
        $ext = strtolower($file->getClientOriginalExtension());
        $ext = in_array($ext, ['png']) ? 'png' : 'jpg';
        $dir = 'profile-photos/'.$user->id;
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $finalPath = $dir.'/profile.'.$ext;
        $oldPath = $user->profile_photo_path;

        try {
            if ($disk->exists($finalPath)) {
                $disk->delete($finalPath);
            }
            $stored = $file->storeAs($dir, 'profile.'.$ext, 'public');
            if (! $stored) {
                return redirect()->route('settings')->with('error', 'Gagal menyimpan foto');
            }
            $finalPath = $stored; // gunakan path dari storeAs
        } catch (\Throwable $e) {
            return redirect()->route('settings')->with('error', 'Gagal menyimpan foto');
        }

        // Update database secara atomic, dan rollback file final jika DB gagal
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $user->profile_photo_path = $finalPath;
            $user->updated_at = now();
            $user->save();
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            // Hapus file final agar konsisten dengan DB lama
            try { $disk->delete($finalPath); } catch (\Throwable $__) {}
            return redirect()->route('settings')->with('error', 'Gagal memperbarui profil');
        }

        // Setelah DB berhasil diupdate, hapus file lama jika berbeda; jangan hapus file final
        try {
            if ($oldPath && $oldPath !== $finalPath) { $disk->delete($oldPath); }
            if (! $disk->exists($finalPath)) {
                return redirect()->route('settings')->with('error', 'Foto hilang setelah pembaruan');
            }
        } catch (\Throwable $__) { /* ignore */ }

        return redirect()->route('settings')->with('success', 'Foto profil diperbarui');
    }

    public function getProfilePhoto(Request $request, \App\Models\User $user)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $path = $user->profile_photo_path;
        if (! $path) {
            return response()->json(['error' => 'not_found'], 404);
        }
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if (! $disk->exists($path)) {
            return response()->json(['error' => 'not_found'], 404);
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
        $bytes = $disk->get($path);

        return response($bytes, 200)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function dinasStatus()
    {
        return view('dinas-status');
    }

    public function dinasStatusData(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));
        $sort = trim((string) $request->query('sort', 'created_at'));
        $dir = strtolower(trim((string) $request->query('dir', 'desc'))) === 'asc' ? 'asc' : 'desc';
        $allowedSort = ['dinas', 'nama_dinas', 'nama_data', 'status', 'created_at'];
        if (! in_array($sort, $allowedSort, true)) {
            $sort = 'created_at';
        }
        $mapSortCol = [
            'dinas' => 'dinas.kode_dinas',
            'nama_dinas' => 'dinas.nama_dinas',
            'nama_data' => 'judul_data',
            'status' => 'status',
            'created_at' => 'created_at',
        ];
        $sortCol = $mapSortCol[$sort] ?? 'created_at';
        $allowedStatus = ['pending', 'approved', 'rejected'];
        $labelToDb = [
            'menunggu persetujuan' => 'pending',
            'disetujui' => 'approved',
            'ditolak' => 'rejected',
        ];
        $status = $labelToDb[strtolower($status)] ?? strtolower($status);
        $qBuilder = \App\Models\DataSubmission::with('dinas')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('judul_data', 'like', '%'.$q.'%')
                        ->orWhereHas('dinas', function ($qd) use ($q) {
                            $qd->where('nama_dinas', 'like', '%'.$q.'%')->orWhere('kode_dinas', 'like', '%'.$q.'%');
                        });
                });
            })
            ->when(in_array($status, $allowedStatus, true), function ($qq) use ($status) {
                $qq->where('status', $status);
            });
        if (str_contains($sortCol, 'dinas.')) {
            $qBuilder = $qBuilder->join('dinas', 'dinas.id', '=', 'data_submissions.dinas_id')->select('data_submissions.*');
        }
        $rows = $qBuilder->orderBy($sortCol, $dir)->limit(500)->get()->map(function ($r) {
            $d = optional($r->dinas);
            $label = $r->status === 'approved' ? 'Disetujui' : ($r->status === 'rejected' ? 'Ditolak' : 'Menunggu Persetujuan');

            return [
                'dinas' => $d->kode_dinas,
                'nama_dinas' => $d->nama_dinas,
                'nama_data' => $r->judul_data,
                'status' => $label,
                'status_key' => $r->status,
                'created_at' => optional($r->created_at)->toDateTimeString(),
            ];
        });

        return response()->json($rows);
    }

    public function monthlyData(Request $request)
    {
        $year = (int) ($request->query('year', now()->year));
        $months = range(1, 12);
        $submissions = [];
        $approvals = [];
        foreach ($months as $m) {
            $submissions[] = DataSubmission::whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
            $approvals[] = DataSubmission::where('status', 'approved')->whereYear('updated_at', $year)->whereMonth('updated_at', $m)->count();
        }

        return response()->json([
            'year' => $year,
            'months' => $months,
            'submissions' => $submissions,
            'approvals' => $approvals,
        ]);
    }

    public function dinasComparison(Request $request)
    {
        $year = (int) ($request->query('year', now()->year));
        $dinasList = Dinas::select('id', 'nama_dinas', 'kode_dinas')->get();
        $grouped = [];
        foreach ($dinasList as $d) {
            $label = $this->normalizeDinasLabel($d->nama_dinas, $d->kode_dinas);
            $count = DataSubmission::where('dinas_id', $d->id)
                ->where('tahun_perencanaan', $year)
                ->count();
            if (! isset($grouped[$label])) {
                $grouped[$label] = 0;
            }
            $grouped[$label] += $count;
        }

        return response()->json([
            'year' => $year,
            'labels' => array_keys($grouped),
            'values' => array_values($grouped),
        ]);
    }

    public function categoryData(Request $request)
    {
        $year = (int) ($request->query('year', now()->year));
        $dinasList = Dinas::select('id', 'nama_dinas', 'kode_dinas')->get();
        $grouped = [];
        foreach ($dinasList as $d) {
            $label = $this->normalizeDinasLabel($d->nama_dinas, $d->kode_dinas);
            $count = DataSubmission::where('dinas_id', $d->id)
                ->where('tahun_perencanaan', $year)
                ->count();
            if (! isset($grouped[$label])) {
                $grouped[$label] = 0;
            }
            $grouped[$label] += $count;
        }

        return response()->json([
            'year' => $year,
            'labels' => array_keys($grouped),
            'values' => array_values($grouped),
        ]);
    }

    protected function normalizeDinasLabel(?string $name, ?string $kode): string
    {
        $n = trim((string) ($name ?? ''));
        $k = trim((string) ($kode ?? ''));

        return $n !== '' ? $n : ($k !== '' ? $k : 'Dinas');
    }

    public function forumThreads(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        $q = trim((string) $request->query('search', ''));
        $cat = trim((string) $request->query('category', ''));
        $org = trim((string) $request->query('org', ''));
        $threads = ForumThread::orderBy('created_at', 'desc')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('title', 'like', '%'.$q.'%');
            })
            ->when($cat !== '', function ($qq) use ($cat) {
                $qq->where('category', $cat);
            })
            ->when($org !== '', function ($qq) use ($org) {
                if (is_numeric($org)) {
                    $qq->where('dinas_id', (int) $org);
                } else {
                    $qq->whereHas('dinas', function ($dq) use ($org) {
                        $dq->where('nama_dinas', $org);
                    });
                }
            })
            ->get()
            ->map(function ($t) {
                $uu = \App\Models\User::find($t->user_id);
                $photo = ($uu && $uu->profile_photo_path) ? route('settings.photo.get', ['user' => $uu->id]) : null;

                return [
                    'id' => $t->id,
                    'title' => $t->title,
                    'category' => $t->category,
                    'content' => $t->content,
                    'dinas_id' => $t->dinas_id,
                    'user_id' => $t->user_id,
                    'author' => optional($uu)->name,
                    'author_photo_url' => $photo,
                    'created_at' => optional($t->created_at)->toDateTimeString(),
                    'replies' => ForumReply::where('thread_id', $t->id)->count(),
                    'likes' => $t->likes ?? 0,
                    'views' => $t->views ?? 0,
                ];
            });

        return response()->json($threads);
    }

    public function forumThreadStore(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $v = $request->validate(['title' => 'required|string|max:80', 'category' => 'required|string|max:60', 'content' => 'required|string|max:500', 'dinas_id' => 'nullable|integer']);
        $did = $v['dinas_id'] ?? $u->dinas_id;
        $row = ForumThread::create(['user_id' => $u->id, 'dinas_id' => $did, 'title' => $v['title'], 'category' => $v['category'], 'content' => $v['content']]);

        return response()->json(['id' => $row->id], 201);
    }

    public function forumThreadDestroy(ForumThread $thread, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin']) && $thread->user_id !== $u->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $title = $thread->title;
        $thread->delete();
        ForumReply::where('thread_id', $thread->id)->delete();
        $this->logActivity('delete', $thread->id, null, ['entity' => ForumThread::class, 'title' => $title]);

        return response()->json(['success' => true]);
    }

    public function forumReset(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($u->role !== 'super_admin') {
            return response()->json(['error' => 'forbidden'], 403);
        }
        ForumReply::query()->delete();
        ForumThread::query()->delete();

        return response()->json(['success' => true]);
    }

    public function forumReplies(ForumThread $thread, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        $q = ForumReply::where('thread_id', $thread->id);
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('forum_replies', 'pinned')) {
                $q = $q->orderBy('pinned', 'desc')->orderBy('created_at', 'asc');
            } else {
                $q = $q->orderBy('created_at', 'asc');
            }
        } catch (\Throwable $__) {
            $q = $q->orderBy('created_at', 'asc');
        }
        $list = $q->get()->map(function ($r) {
            $uu = \App\Models\User::find($r->user_id);
            $photo = ($uu && $uu->profile_photo_path) ? route('settings.photo.get', ['user' => $uu->id]) : null;

            return [
                'id' => $r->id,
                'content' => $r->content,
                'user_id' => $r->user_id,
                'username' => optional($uu)->name,
                'photo_url' => $photo,
                'created_at' => optional($r->created_at)->toDateTimeString(),
                'pinned' => (bool) ($r->pinned ?? false),
            ];
        });

        return response()->json($list);
    }

    public function forumThreadLike(ForumThread $thread, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        try {
            $thread->increment('likes');
        } catch (\Throwable $__) {
        }

        return response()->json(['likes' => (int) ($thread->likes ?? 0)]);
    }

    public function forumReplyStore(ForumThread $thread, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $v = $request->validate(['content' => 'required|string|max:500']);
        $row = ForumReply::create(['thread_id' => $thread->id, 'user_id' => $u->id, 'content' => $v['content']]);

        return response()->json(['id' => $row->id], 201);
    }

    public function forumReplyDestroy(ForumThread $thread, ForumReply $reply, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($reply->thread_id !== $thread->id) {
            return response()->json(['error' => 'bad_request'], 400);
        }
        if (! in_array($u->role, ['super_admin']) && $reply->user_id !== $u->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $reply->delete();
        $this->logActivity('delete', $reply->id, null, ['entity' => ForumReply::class]);

        return response()->json(['success' => true]);
    }

    public function forumReplyPin(ForumThread $thread, ForumReply $reply, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($reply->thread_id !== $thread->id) {
            return response()->json(['error' => 'bad_request'], 400);
        }
        if (! in_array($u->role, ['super_admin']) && $thread->user_id !== $u->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        try {
            $reply->pinned = true;
            $reply->save();
        } catch (\Throwable $__) {
            return response()->json(['error' => 'not_supported'], 400);
        }
        $this->logActivity('forum_reply_pin', $reply->id, null, ['entity' => ForumReply::class, 'thread_id' => $thread->id]);

        return response()->json(['success' => true]);
    }

    public function forumReplyUnpin(ForumThread $thread, ForumReply $reply, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if ($reply->thread_id !== $thread->id) {
            return response()->json(['error' => 'bad_request'], 400);
        }
        if (! in_array($u->role, ['super_admin']) && $thread->user_id !== $u->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        try {
            $reply->pinned = false;
            $reply->save();
        } catch (\Throwable $__) {
            return response()->json(['error' => 'not_supported'], 400);
        }
        $this->logActivity('forum_reply_unpin', $reply->id, null, ['entity' => ForumReply::class, 'thread_id' => $thread->id]);

        return response()->json(['success' => true]);
    }

    public function calendarEvents(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        $month = trim((string) $request->query('month', ''));
        $type = trim((string) $request->query('type', ''));
        $q = CalendarEvent::orderBy('date', 'asc');
        if ($month !== '') {
            $parts = explode('-', $month);
            if (count($parts) >= 2) {
                $q->whereYear('date', (int) $parts[0])->whereMonth('date', (int) $parts[1]);
            }
        }
        if ($type !== '') {
            $q->where('type', $type);
        }
        $rows = $q->get()->map(function ($e) {
            return ['id' => $e->id, 'name' => $e->name, 'date' => $e->date->format('Y-m-d'), 'type' => $e->type, 'color' => $e->color, 'dinas_id' => $e->dinas_id];
        });

        return response()->json($rows);
    }

    public function calendarEventStore(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $v = $request->validate(['name' => 'required|string|max:120', 'date' => 'required|date', 'type' => 'nullable|string|max:60', 'color' => 'nullable|string|max:9', 'dinas_id' => 'nullable|integer']);
        $did = $v['dinas_id'] ?? $u->dinas_id;
        $row = CalendarEvent::create(['user_id' => $u->id, 'dinas_id' => $did, 'name' => $v['name'], 'date' => $v['date'], 'type' => $v['type'] ?? '', 'color' => $v['color'] ?? null]);

        $this->logActivity('create', $row->id, null, ['entity' => \App\Models\CalendarEvent::class, 'name' => $row->name, 'date' => optional($row->date)->format('Y-m-d'), 'type' => $row->type]);

        return response()->json(['id' => $row->id], 201);
    }

    public function calendarEventUpdate(CalendarEvent $event, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin']) && $event->user_id !== $u->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $v = $request->validate(['name' => 'required|string|max:120', 'date' => 'required|date', 'type' => 'nullable|string|max:60', 'color' => 'nullable|string|max:9']);
        $event->update(['name' => $v['name'], 'date' => $v['date'], 'type' => $v['type'] ?? '', 'color' => $v['color'] ?? null]);

        $this->logActivity('update', $event->id, null, ['entity' => \App\Models\CalendarEvent::class, 'name' => $event->name, 'date' => optional($event->date)->format('Y-m-d'), 'type' => $event->type]);

        return response()->json(['success' => true]);
    }

    public function calendarEventDestroy(CalendarEvent $event, Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        if (! in_array($u->role, ['super_admin']) && $event->user_id !== $u->id) {
            return response()->json(['error' => 'forbidden'], 403);
        }
        $meta = ['entity' => \App\Models\CalendarEvent::class, 'name' => $event->name, 'date' => optional($event->date)->format('Y-m-d'), 'type' => $event->type];
        $event->delete();
        $this->logActivity('delete', $event->id, null, $meta);

        return response()->json(['success' => true]);
    }

    public function dinasList(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        $list = \App\Models\Dinas::orderBy('nama_dinas', 'asc')->get()->map(function ($d) {
            return ['id' => $d->id, 'name' => $d->nama_dinas];
        });

        return response()->json($list);
    }

    public function notifications(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        $since = trim((string) $request->query('since', ''));
        $q = \App\Models\ActivityLog::with(['dinas'])
            ->where('entity', \App\Models\CalendarEvent::class)
            ->orderBy('created_at', 'desc');
        if ($u->role === 'admin_dinas' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id);
        }
        if ($u->role === 'user' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id);
        }
        if ($since !== '') {
            $q->where('created_at', '>', $since);
        }
        $rows = $q->limit(50)->get()->map(function ($r) {
            $meta = $r->metadata ?: [];
            $action = $r->action;
            $isCreate = $action === 'create';
            $isUpdate = $action === 'update';
            $isDelete = $action === 'delete';
            $title = $isCreate ? 'Agenda ditambahkan' : ($isUpdate ? 'Agenda diperbarui' : ($isDelete ? 'Agenda dihapus' : 'Agenda'));
            $descParts = [];
            if (! empty($meta['name'])) { $descParts[] = $meta['name']; }
            if (! empty($meta['date'])) { $descParts[] = $meta['date']; }
            if (! empty($meta['type'])) { $descParts[] = $meta['type']; }
            $desc = implode(' • ', $descParts);

            return [
                'id' => $r->id,
                'title' => $title,
                'description' => $desc ?: (optional($r->dinas)->nama_dinas ?: ''),
                'timestamp' => optional($r->created_at)->toIso8601String(),
                'unread' => $r->read_at === null,
                'opd' => optional($r->dinas)->nama_dinas,
            ];
        });

        return response()->json($rows);
    }

    public function notificationsMarkAllRead(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $q = \App\Models\ActivityLog::query()->where('entity', \App\Models\CalendarEvent::class)->whereNull('read_at');
        if ($u->role === 'admin_dinas' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id);
        }
        if ($u->role === 'user' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id);
        }
        $count = 0;
        try {
            $count = $q->count();
            $q->update(['read_at' => now()]);
        } catch (\Throwable $__) {
            return response()->json(['error' => 'db_write_failed'], 500);
        }

        return response()->json(['success' => true, 'updated' => $count]);
    }

    public function activityLogs(Request $request)
    {
        $u = $request->user();
        if (! $u) {
            return response()->json([], 401);
        }
        $size = max(1, min(50, (int) $request->query('size', 10)));
        $page = max(1, (int) $request->query('page', 1));
        $opd = trim((string) $request->query('opd', ''));
        $since = trim((string) $request->query('since', ''));
        $q = \App\Models\ActivityLog::with(['user', 'dinas'])->orderBy('created_at', 'desc');
        if ($opd !== '') {
            $q->whereHas('dinas', function ($qq) use ($opd) {
                $qq->where('nama_dinas', $opd);
            });
        }
        if ($u->role === 'admin_dinas' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id);
        }
        if ($u->role === 'user' && $u->dinas_id) {
            $q->where('dinas_id', $u->dinas_id)->where('user_id', $u->id);
        }
        if ($since !== '') {
            $q->where('created_at', '>', $since);
        }
        $rows = $q->skip(($page - 1) * $size)->take($size)->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'timestamp' => optional($r->created_at)->toIso8601String(),
                'action' => $r->action,
                'username' => optional($r->user)->name,
                'role' => $r->role,
                'record_id' => $r->record_id,
                'approval_status' => $r->approval_status,
                'opd' => optional($r->dinas)->nama_dinas,
                'entity' => $r->entity,
                'metadata' => $r->metadata,
            ];
        });

        return response()->json($rows);
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
