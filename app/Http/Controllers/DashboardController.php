<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use App\Models\Dinas;
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
        $targetGlobal = $totalDinas * 10; //target per dinas 10 data submission
        $progresspercent = $targetGlobal > 0 ? ($totalDataSubmissions / $targetGlobal) * 100 : 0;
        return view('dashboard', compact('totalDinas', 'totalDataSubmissions', 'pendingData', 'approvedData', 'progresspercent'));
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
