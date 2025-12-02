<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\DataSubmission;

class HomeController extends Controller
{
    public function index()
    {
        $totalDinas = Dinas::count();
        $totalSubmissions = DataSubmission::count();
        $approvedSubmissions = DataSubmission::where('status', 'approved')->count();
        
        $completionRate = $totalSubmissions > 0 ? round(($approvedSubmissions / $totalSubmissions) * 100, 0) : 0;
        
        return view('index', [
            'totalDinas' => $totalDinas,
            'totalSubmissions' => $totalSubmissions,
            'completionRate' => $completionRate
        ]);
    }
}