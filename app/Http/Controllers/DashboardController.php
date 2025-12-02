<?php

namespace App\Http\Controllers;

use App\Models\DataSubmission;
use App\Models\Dinas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalData = DataSubmission::byUserRole()->count();
        $pendingData = DataSubmission::byUserRole()->where('status', 'pending')->count();
        $approvedData = DataSubmission::byUserRole()->where('status', 'approved')->count();

        // Data untuk chart (contoh sederhana)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            'data' => [65, 59, 80, 81, 56, 55]
        ];

        return view('dashboard', compact('user', 'totalData', 'pendingData', 'approvedData', 'chartData'));
    }

    public function dinasStatus()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            // Super Admin lihat semua dinas
            $dinas = Dinas::withCount(['submissions as total_data', 
                                      'submissions as pending_data' => function($query) {
                                          $query->where('status', 'pending');
                                      },
                                      'submissions as approved_data' => function($query) {
                                          $query->where('status', 'approved');
                                      }])->get();
        } else {
            // Admin Dinas & User hanya lihat dinas mereka
            $dinas = Dinas::where('id', $user->dinas_id)
                        ->withCount(['submissions as total_data', 
                                   'submissions as pending_data' => function($query) {
                                       $query->where('status', 'pending');
                                   },
                                   'submissions as approved_data' => function($query) {
                                       $query->where('status', 'approved');
                                   }])->get();
        }

        return view('dinas-status', compact('user', 'dinas'));
    }

    public function reports()
    {
        $user = Auth::user();
        $data = DataSubmission::byUserRole()->with(['user', 'dinas'])->get();
        
        return view('reports', compact('user', 'data'));
    }

    public function calendar()
    {
        $user = Auth::user();
        return view('calendar', compact('user'));
    }

    public function forum()
    {
        $user = Auth::user();
        return view('forum', compact('user'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }
}