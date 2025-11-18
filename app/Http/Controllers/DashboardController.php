<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\DataSubmission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        ->middleware('auth');
    }

    public function index()
    {
         = Dinas::count();
         = DataSubmission::count();
         = DataSubmission::where('status', 'pending')->count();
        
        return view('dashboard', [
            'dinasCount' => ,
            'submissionsCount' => ,
            'pendingSubmissions' => 
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
