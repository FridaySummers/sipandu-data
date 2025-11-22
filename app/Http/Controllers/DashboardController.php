<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use App\Models\Dinas;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dinasCount = class_exists(Dinas::class) ? (int) Dinas::count() : 0;
        $submissionsCount = class_exists(DataSubmission::class) ? (int) DataSubmission::count() : 0;
        $pendingSubmissions = class_exists(DataSubmission::class) ? (int) DataSubmission::where('status', 'pending')->count() : 0;

        return view('dashboard', [
            'dinasCount' => $dinasCount,
            'submissionsCount' => $submissionsCount,
            'pendingSubmissions' => $pendingSubmissions,
        ]);
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

    public function settings()
    {
        return view('settings');
    }

    public function dinasStatus()
    {
        return view('dinas-status');
    }
}
